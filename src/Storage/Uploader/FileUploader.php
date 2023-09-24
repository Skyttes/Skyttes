<?php declare(strict_types=1);

namespace Skyttes\Storage\Uploader;

use Nette\Http\FileUpload;
use Skyttes\Storage\Exception\UploadException;
use Skyttes\Storage\Exception\WrongMimeTypeException;
use Skyttes\Storage\StorageRepositoryInterface;

class FileUploader implements UploaderInterface
{
    public function __construct(
        private StorageRepositoryInterface $repository,
        private array $allowedMimeTypes = []
    ) {
    }

    public function upload(FileUpload $file, string $namespace, string|null $id = null, array $args = []): string
    {
        if (!$file->isOk()) {
            throw new UploadException("Failed to process provided file.");
        }

        if (!$this->isValid($file)) {
            throw new WrongMimeTypeException($this->allowedMimeTypes, $file->getContentType());
        }

        $upload = $this->repository->processUpload($file, $namespace, $id, $args);

        return $this->repository->getPublicPath($upload);
    }

    public function isValid(FileUpload $upload): bool
    {
        return in_array($upload->getContentType(), $this->allowedMimeTypes, true);
    }
}