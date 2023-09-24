<?php declare(strict_types=1);

namespace Skyttes\Storage;

use Nette\Http\FileUpload;

interface StorageRepositoryInterface {
    public function getExtension(string $path): string;

    public function exists(string $path): bool;

    public function createDirectory(string $path): void;

    public function createFile(string $path, string $content): string;

    public function deleteFile(string $path): bool;

    public function isRealPath(string $path): bool;
    
    public function isPublic(string $path): bool;

    public function getPublicPath(string $path): string;
    
    public function getRealPath(string $path): string;

    public function joinPath(string $path): string;
    
    public function processUpload(FileUpload $file, string $namespace, string $id, array $args = []): string;
}