<?php declare(strict_types=1);

namespace Skyttes\Application;

use Nette\Application\Application;
use Nette\Bootstrap\Configurator;
use Nette\DI\Container;
use RuntimeException;

final class Kernel {
    public ?Container $container = null;

    public static ?Kernel $kernel = null;

    public readonly Params $params;

    public readonly string $rootDir;

    public readonly string $appDir;

    public readonly string $configDir;

    public readonly string $logDir;

    public readonly string $publicDir;

    public readonly string $tempDir;

    public function __construct(
        string $rootDir,
        public readonly bool $debugMode = false,
        /** @var string[] */
        public readonly array $configs = [],
        /** @var string[] */
        public readonly array $languages = ["en"],
        ?string $appDir = null,
        ?string $configDir = null,
        ?string $logDir = null,
        ?string $publicDir = null,
        ?string $tempDir = null,
    ) {
        $this->params = new Params();
        $this->rootDir = realpath($rootDir);

        $this->appDir = $appDir ?? realpath($this->rootDir . "/src");
        $this->configDir = $configDir ?? realpath($this->rootDir . "/config");
        $this->logDir = $logDir ?? realpath($this->rootDir . "/logs");
        $this->publicDir = $publicDir ?? realpath($this->rootDir . "/public");
        $this->tempDir = $tempDir ?? realpath($this->rootDir . "/temp");
    }

    public function configure(): Configurator
    {
        $configurator = new Configurator();

        $configurator->setTempDirectory($this->tempDir);
        $configurator->setTimeZone("Europe/Prague");

        $configurator->addStaticParameters([
            "env" => getenv()
        ]);

        foreach ($this->configs as $fileName) {
            $fileName = ltrim(rtrim($fileName, "/"), "/");
            $fileName = trim($fileName);

            if (!str_ends_with($fileName, ".neon")) {
                $fileName = "$fileName.neon";
            }

            $configurator->addConfig(
                    $this->configDir . "/" . $fileName,
            );
        }

        $configurator->setDebugMode($this->debugMode);

        $configurator->enableTracy($this->logDir);

        $configurator->addStaticParameters([
            "rootDir" => $this->rootDir,
            "appDir" => $this->appDir,
            "publicDir" => $this->publicDir,
            "configDir" => $this->configDir,
            "logDir" => $this->logDir,
            "tempDir" => $this->tempDir,
        ]);

        $configurator
            ->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();

        return $configurator;
    }

    /**
     * @template T of class-string
     * @param T $type
     * @return T
     */
    public function run(string $type = Application::class, ?callable $configure = null)
    {
        Kernel::$kernel = $this;
        $configurator = static::configure();

        if (is_callable($configure)) {
            call_user_func($configure, $configurator, $this->params);
        }
        $this->params->lock();

        $container = $configurator->createContainer();
        $this->container = $container;

        $instance = $container->getByType($type);
        $instance->run();

        return $instance;
    }

    public static function getKernel(): Kernel
    {
        if (!self::$kernel) {
            throw new RuntimeException("Kernel is not bound");
        }

        return self::$kernel;
    }

    public static function getContainer(): Container
    {
        $container = self::getKernel()->container;

        if (!$container) {
            throw new RuntimeException("Container is not bound");
        }

        return $container;
    }

    /**
     * @return string[]
     */
    public static function getLanguages(): array
    {
        $languages = self::getKernel()->languages;

        if (!$languages) {
            throw new RuntimeException("Languages are not set");
        }

        return $languages;
    }

    public static function getParams(): Params {
        $params = self::getKernel()->params;

        if (!$params) {
            throw new RuntimeException("Params are not set");
        }

        return $params;
    }

}