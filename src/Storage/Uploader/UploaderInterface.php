<?php declare(strict_types=1);

namespace Skyttes\Storage\Uploader;
use Nette\Http\FileUpload;

interface UploaderInterface {
    public function upload(FileUpload $file, string $namespace, ?string $id = null, bool $copy = false, array $args = []): string;

    public function isValid(FileUpload $upload): bool;
}