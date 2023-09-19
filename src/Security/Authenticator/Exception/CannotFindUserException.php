<?php declare(strict_types=1);

namespace Skyttes\Security\Authenticator\Exception;

use Skyttes\Security\Exception\AuthException;

class CannotFindUserException extends AuthException {
    public ?string $identifier = null;
    
}