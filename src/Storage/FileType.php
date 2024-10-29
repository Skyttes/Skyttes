<?php declare(strict_types=1);

namespace Skyttes\Storage;

use Nette\Http\FileUpload;

class FileType
{
    public const IMAGE_TYPES = ['image/gif', 'image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'];

    public const PDF_TYPES = ["application/pdf"];

    /**
     * @param string[] $types
     */
    public static function isOneOfMimeTypes(FileUpload $file, array $types): bool
    {
        return $file->isOk() && in_array($file->getContentType(), $types, true);
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