<?php declare(strict_types=1);

namespace Skyttes\Doctrine\Positionable;

interface PositionedInterface {
    public function getPosition(): int;
    
    public function setPosition(int $position): self;
    
}