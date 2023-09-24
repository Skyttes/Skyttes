<?php declare(strict_types=1);

namespace Skyttes\Storage\Exception;

use RuntimeException;
use Throwable;

class UploadException extends RuntimeException {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}