<?php declare(strict_types=1);

namespace Skyttes\Utils;

use Nette\Utils\Image;
use Imagick;
use RuntimeException;

class Images {
    public static function resizeImage(string $path, int $maxWidth = 1300, ?int $maxHeight = null, bool $exact = false): void
    {
        $image = Image::fromFile($path);
        $image->resize(
            min($image->getWidth(), $maxWidth), 
            !$maxHeight ? null : min($image->getHeight(), $maxHeight), 
            $exact ? Image::EXACT : Image::FIT
        );
        $image->save($path);
    }

    public static function stripImage(string $path, int $compressionQuality = 70): void
    {
        if (!class_exists(Imagick::class)) {
            $im = new Imagick();
            $im->readImage($path);
            $im->setImageCompressionQuality($compressionQuality);
            $im->stripImage();
            $im->writeImage($path);
            $im->destroy();
        } else {
            trigger_error("Imagick is required for " . self::class . "::stripImage()", E_USER_WARNING);
        }
    }

    public static function convertToWebp(string $path): string
    {
        $ext = pathinfo($path)["extension"];

        if ($ext === 'svg') {
            trigger_error(".svg files are ignored and webp conversion is not supported.");
            return $path;
        }

        if (!function_exists('imagewebp')) {
            trigger_error("imagewebp function is required to convert images to webp", E_USER_WARNING);
        }

        $image = Image::fromFile($path)->getImageResource();

        $result = imagewebp($image, $path, 100);
        if ($result === false) {
            throw new RuntimeException('Failed to convert the provided image to webp format');
        }

        // Destroy image to free memory
        imagedestroy($image);

        $newPath = str_replace($ext, '', $path) . 'webp';

        rename($path, $newPath);
        $path = $newPath;

        return $path;
    }

}