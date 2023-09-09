<?php declare(strict_types=1);

namespace Skyttes\Security\User;

interface UserServiceInterface
{
    public function findByIdentifier(string $identifier): ?UserInterface;
    
}