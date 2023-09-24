<?php declare(strict_types=1);

namespace Skyttes\Storage\Uploader;

use Nette\Http\FileUpload;
use Skyttes\Storage\StorageRepositoryInterface;
use Skyttes\Utils\Images;

class ImageUploader extends FileUploader implements UploaderInterface
{
    public const MIME_TYPES = [
        'image/png',
        'image/jpeg',
        'image/webp',
        'image/svg+xml',
        'image/gif',
    ];

    public function __construct(StorageRepositoryInterface $repository) {
        parent::__construct($repository, self::MIME_TYPES);
    }

    public function upload(FileUpload $file, string $namespace, string|null $id = null, array $args = []): string
    {
        $upload = parent::upload($file, $namespace, $id, $args);

        if (!empty($args["image.optimize"])) {
            if (!(isset($args["image.strip"]) && $args["image.strip"] === false)) {
                Images::stripImage($upload, $args["image.strip.compressionQuality"] ?? 70);
            }

            if (!empty($args["image.resize"])) {
                Images::resizeImage($upload, $args["image.resize.maxWidth"] ?? 1300, $args["image.resize.maxHeight"] ?? null, $args["image.resize.exact"] ?? false);
            }

            if (!(isset($args["image.webp"]) && $args["image.webp"] === false)) {
                $upload = Images::convertToWebp($upload);
            }
        }

        return $this->repository->getPublicPath($upload);
    }
}