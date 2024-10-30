<?php declare(strict_types=1);

namespace Skyttes\Security\User;

interface UserServiceInterface
{
    public function findById(string $id): ?UserInterface;
    
    public function findByPresentingIdentifier(string $identifier): ?UserInterface;
}