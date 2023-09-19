<?php declare(strict_types=1);

namespace Skyttes\Assets;

use Nette\Utils\FileSystem;
use Nette\Utils\Html;
use Nette\Utils\Json;
use RuntimeException;
use function curl_close;
use function curl_error;
use function curl_exec;
use function curl_init;
use function curl_setopt_array;
use function fopen;
use const CURLOPT_CUSTOMREQUEST;
use const CURLOPT_FILE;
use const CURLOPT_HEADER;
use const CURLOPT_NOSIGNAL;
use const CURLOPT_PROTOCOLS;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_SSL_VERIFYHOST;
use const CURLOPT_SSL_VERIFYPEER;
use const CURLOPT_TIMEOUT_MS;
use const CURLPROTO_HTTP;
use const CURLPROTO_HTTPS;
use const PHP_OS_FAMILY;

class Vite
{
    public bool $hasDev;

    public function __construct(
        private readonly string $manifest,
        private readonly string $baseUrl = "/",
        public readonly string|null $devServer = null,
        public string|null $devServerPublic = null,
        private readonly int $devServerTimeout = 100,
        private readonly bool $forceDevServer = false,
    )
    {
        if (!$this->devServerPublic) {
            $this->devServerPublic = $this->devServer;
        }
        $this->hasDev = $this->shouldUseDevServer();
    }

    public function shouldUseDevServer(): bool
    {
        if (empty($this->devServer)) {
            return false;
        }

        if ($this->forceDevServer) {
            return true;
        }

        if ($curl = curl_init($this->devServer)) {
            curl_setopt_array($curl, [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
                CURLOPT_RETURNTRANSFER => false,
                CURLOPT_HEADER => false,
                CURLOPT_FILE => fopen('php://temp', 'w+'),
                CURLOPT_TIMEOUT_MS => $this->devServerTimeout * 1000,
                CURLOPT_NOSIGNAL => $this->devServerTimeout < 1 && PHP_OS_FAMILY !== 'Windows',
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);

            curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);
            return $error === '';
        }

        return false;
    }

    public function forEntry(string $entry): array
    {
        if (!file_exists($this->manifest)) {
            throw new RuntimeException('Missing manifest file: ' . $this->manifest);
        }

        $manifest = Json::decode(FileSystem::read($this->manifest), Json::FORCE_ARRAY);
        $scripts = [$manifest[$entry]['file']];
        $styles = $manifest[$entry]['css'] ?? [];

        return [
            "styles" => $styles,
            "scripts" => $scripts,
        ];
    }

    public function createScripts(array $data): void
    {
        foreach ($data['scripts'] as $path) {
            echo Html::el('script')->type('module')->defer("")->src($this->baseUrl . $path);
        }
    }

    public function createStyles(array $data): void
    {
        foreach ($data['styles'] as $path) {
            echo Html::el('link')->rel('stylesheet')->href($this->baseUrl . $path);
        }
    }

    public function createViteClient(): void
    {
        echo Html::el('script')->type('module')->src($this->devServerPublic . "/@vite/client");
    }

    public function createViteClientEntry(string $entry): void
    {
        echo Html::el('script')->type('module')->src($this->devServerPublic . "/$entry");
    }
}