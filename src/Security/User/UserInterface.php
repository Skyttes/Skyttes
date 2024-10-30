<?php declare(strict_types=1);

namespace Skyttes\Security\User;

interface UserInterface
{
    public function getId(): string;

    public function getPresentingIdentifier(): string;

    public function getPasswordHash(): string;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;

    /**
     * @return string[]|string|null|
     */
    public function getRole(): string|array|null;
    
}