<?php declare(strict_types=1);

namespace Skyttes\Storage;

use Nette\Http\FileUpload;
use RuntimeException;

class StorageRepository implements StorageRepositoryInterface
{
    public function __construct(
        public readonly string $publicDir,
        public readonly string $publicRoot,
        private string $dataDir = "data"
    ) {
        $this->dataDir = realpath(implode([$publicDir, $dataDir], DIRECTORY_SEPARATOR));

        $this->createDirectory($this->dataDir);
    }

    public function getExtension(string $path): string
    {
        return pathinfo($path)["extension"] ?? '';
    }

    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    public function createDirectory(string $path): void
    {
        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true) && !is_dir($path)) {
                throw new RuntimeException(sprintf('Cannot create directory "%s"', $path));
            }
        }
    }

    public function createFile(string $path, string $content): string
    {
        $path = realpath($path);

        file_put_contents($path, $content);

        return $path;
    }

    public function deleteFile(string $path): bool
    {
        if (!file_exists($path))
            return true;

        return unlink($path);
    }

    public function isPublicPath(string $path): bool
    {
        return str_contains($path, $this->publicDir);
    }

    public function getPublicPath(string $path): string
    {
        if (!$this->isPublicPath($path)) {
            throw new RuntimeException("Path is not in public dir");
        }

        return str_replace($this->publicDir, $this->publicRoot, $path);
    }

    public function joinPath(string $path): string
    {
        return realpath($this->publicDir . DIRECTORY_SEPARATOR . $path);
    }

    public function isImage(FileUpload $image): bool
    {
        return $image->isOk() && in_array($image->getContentType(), self::IMAGES, true);
    }

    public function isPdf(FileUpload $image): bool
    {
        return $image->isOk() && (!$image->getContentType() || $image->getContentType() === "application/pdf");
    }

    protected function processUpload(FileUpload $file, string $namespace, string $id, bool $copy = false): string
    {
        $dir = $this->joinPath($namespace);
        $ext = $this->getExtension($file->getSanitizedName());
        
        $this->createDirectory($dir);

        $fileName = sprintf('%s.%s', $id, $ext);

        $path = realpath($dir . DIRECTORY_SEPARATOR . $fileName);
        $tmpPath = $file->getTemporaryFile();

        if ($copy) {
            copy($tmpPath, $path);
        } elseif (is_uploaded_file($tmpPath)) {
            move_uploaded_file($tmpPath, $path);
        } else {
            rename($tmpPath, $path);
        }

        return $path;
    }

    public function uploadImage(FileUpload $file, string $namespace, string $id, bool $copy = false): string
    {
        if ($this->isImage($file)) {
            return $this->getUrlPath(
                $this->optimizeImage(
                    $this->processUpload($file, $namespace, $id, $copy)
                )
            );
        }

        throw new RuntimeException("Failed to process the uploaded file");
    }

    public function uploadPdf(FileUpload $file, string $namespace, string $id, bool $copy = false): string
    {
        if ($this->isPdf($file)) {
            return $this->getUrlPath($this->processUpload($file, $namespace, $id, $copy));
        }

        throw new RuntimeException("Failed to process the uploaded file");
    }

}