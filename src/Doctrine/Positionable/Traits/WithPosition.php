<?php declare(strict_types=1);

namespace Skyttes\Doctrine\Positionable\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait WithPosition {
    #[ORM\Column(type: Types::INTEGER)]
    public int $position;

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;
        return $this;
    }
    
}