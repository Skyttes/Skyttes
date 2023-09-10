<?php declare(strict_types=1);

namespace Skyttes\Core\Storage;

interface StorageRepositoryInterface {
    public const IMAGES = [
        'image/png', 
        'image/jpeg', 
        'image/webp', 
        'image/svg+xml',
        'image/gif', 
    ];

    public function getExtension(string $path): string;

    public function exists(string $path): bool;

    public function createDirectory(string $path): void;

    public function createFile(string $path, string $content): string;

    public function deleteFile(string $path): bool;

    public function isPublicPath(string $path): bool;

    public function getPublicPath(string $path): string;

    public function joinPath(string $path): string;
    
    public function isImage(FileUpload $image): bool;

    public function isPdf(FileUpload $image): bool;

    protected function processUpload(FileUpload $file, string $namespace, string $id, bool $copy = false): string;

    public function uploadImage(FileUpload $file, string $namespace, string $id, bool $copy = false): string;

    public function uploadPdf(FileUpload $file, string $namespace, string $id, bool $copy = false): string;
}