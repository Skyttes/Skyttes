<?php declare(strict_types=1);

namespace Skyttes\Storage\Exception;

use Throwable;

class WrongMimeTypeException extends UploadException {
    public function __construct(?array $allowedMimeTypes = null, ?string $providedMimeType = null, int $code = 0, Throwable $previous = null) {
        $message = "Wrong MIME type provided";

        if (!empty($allowedMimeTypes)) {
            $message .= sprintf(". Allowed MIME types are: %s", implode(", ", $allowedMimeTypes));
        }

        $message .= $providedMimeType ? sprintf(", provided: %s", $providedMimeType) : ".";
        

        parent::__construct($message, $code, $previous);
    }
}