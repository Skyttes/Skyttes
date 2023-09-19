<?php declare(strict_types=1);

namespace Skyttes\Doctrine\Traits\Timestamps;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * Trait for entities that adds the updatedAt field.
 */
trait WithUpdatedTimestamp
{
  #[Column(type: Types::DATETIME_MUTABLE, nullable: true)]
  private ?DateTimeInterface $updatedAt;

  public function getUpdatedAt(): ?DateTimeInterface
  {
    return $this->updatedAt;
  }

  /**
   * @internal
   */
  #[PrePersist]
  #[PreUpdate]
  public function addUpdatedTimestamp(): void
  {
    $this->updatedAt = new DateTime();
  }
}
