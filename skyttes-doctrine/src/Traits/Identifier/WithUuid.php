<?php declare(strict_types=1);

namespace Skyttes\Doctrine\Traits\Identifier;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Ramsey\Uuid\Doctrine\UuidGenerator;

trait WithUuid
{
  #[Column]
  #[Id]
  #[GeneratedValue(strategy: "CUSTOM")]
  #[CustomIdGenerator(class: UuidGenerator::class)]
  private string $id;

  public function getId(): string
  {
    return $this->id;
  }

  public function setId(string $id): static
  {
    $this->id = $id;
    return $this;
  }
}
