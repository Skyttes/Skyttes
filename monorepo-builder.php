<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;

function package(string $name): string {
    return __DIR__ . DIRECTORY_SEPARATOR . "skyttes-$name";
}

return static function (MBConfig $mbConfig): void {
    $mbConfig->packageDirectories(array_map(package(...), [
        "core",
        "doctrine",
        "security",
        "vite"
    ]));
};
