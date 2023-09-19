<?php declare(strict_types=1);

namespace Skyttes\Doctrine\Traits\Identifier;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

trait WithId
{
  #[Id]
  #[Column(type: Types::INTEGER)]
  #[GeneratedValue]
  private int $id;

  public function getId(): int
  {
    return $this->id;
  }

  public function setId(int $id): static
  {
    $this->id = $id;
    return $this;
  }
}
