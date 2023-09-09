<?php declare(strict_types=1);

namespace Skyttes\Core\FileUpload;

use Nette\Http\FileUpload;

class FileUploadMocker
{
    public static function mock(string $fileName) {
        return new FileUpload([
            "name" => basename($fileName),
            "size" => filesize($fileName) ?? 0,
            "tmp_name" => $fileName,
            "error" => UPLOAD_ERR_OK,
        ]);
    }
}
