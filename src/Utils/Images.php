<?php declare(strict_types=1);

namespace Skyttes\Utils;

use Nette\Utils\Image;
use Imagick;
use RuntimeException;

class Images {
    public static function resizeImage(string $path, int $maxWidth = 1300, ?int $maxHeight = null, bool $exact = false, ?string $outPath = null): string
    {
        $outPath = $outPath ?? $path;

        $image = Image::fromFile($path);
        $image->resize(
            min($image->getWidth(), $maxWidth),
            !$maxHeight ? null : min($image->getHeight(), $maxHeight),
            $exact ? Image::EXACT : Image::FIT
        );

        $image->save($outPath);

        return $outPath;
    }

    public static function stripImage(string $path, int $compressionQuality = 70, ?string $outPath = null): string
    {
        if (!class_exists(Imagick::class)) {
            trigger_error("Imagick is required for " . self::class . "::stripImage()", E_USER_WARNING);
        }

        $outPath = $outPath ?? $path;

        $im = new Imagick();
        $im->readImage($path);
        $im->setImageCompressionQuality($compressionQuality);
        $im->stripImage();
        $im->writeImage($outPath);
        $im->destroy();

        return $outPath;
    }

    public static function convertToWebp(string $path, int $quality = 100, ?string $outPath = null): string
    {
        if (!function_exists('imagewebp')) {
            trigger_error("imagewebp function is required to convert images to webp", E_USER_WARNING);
        }

        $pathInfo = pathinfo($path);

        if (!is_array($pathInfo)) {
            throw new RuntimeException("Malformed path provided.");
        }

        if ($pathInfo["extension"] === 'svg') {
            trigger_error(".svg files are ignored and webp conversion is not supported.");
            return $path;
        }

        $image = Image::fromFile($path)->getImageResource();

        if (!$outPath) {
            $outPath = str_replace($pathInfo["extension"], "webp", $path);
        }

        $result = imagewebp($image, $outPath, $quality);
        if (!$result) {
            throw new RuntimeException('Failed to convert the provided image to webp format');
        }

        // Destroy image to free memory
        imagedestroy($image);

        return $outPath;
    }

}