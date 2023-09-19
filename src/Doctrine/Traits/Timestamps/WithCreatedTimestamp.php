<?php declare(strict_types=1);

namespace Skyttes\Doctrine\Traits\Timestamps;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;

trait WithCreatedTimestamp
{
  #[Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
  private DateTimeImmutable $createdAt;

  public function getCreatedAt(): DateTimeImmutable
  {
    return $this->createdAt;
  }

  /**
   * @internal
   */
  #[PrePersist]
  public function addCreatedTimestamp(): void
  {
      if (empty($this->createdAt)) {
          $this->createdAt = new DateTimeImmutable();
      }
  }
}
