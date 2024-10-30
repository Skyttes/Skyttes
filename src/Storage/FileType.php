<?php declare(strict_types=1);

namespace Skyttes\Storage;

use Nette\Http\FileUpload;

class FileType
{
    public const IMAGE_TYPES = ['image/gif', 'image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'];

    public const PDF_TYPES = ["application/pdf"];

    public static function isValidFile(FileUpload $file): bool
    {
        return $file->isOk() && filesize($file->getTemporaryFile()) >= 1;
    }

    /**
     * @param string[] $types
     */
    public static function isOneOfMimeTypes(FileUpload $file, array $types): bool
    {
        return static::isValidFile($file) && in_array($file->getContentType(), $types, true);
    }

    public static function isImage(FileUpload $file): bool
    {
        return static::isOneOfMimeTypes($file, static::IMAGE_TYPES);
    }

    public static function isPdf(FileUpload $file): bool
    {
        return static::isOneOfMimeTypes($file, static::PDF_TYPES);
    }
}