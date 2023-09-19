<?php declare(strict_types=1);

namespace Skyttes\Doctrine\Traits\Timestamps;

trait WithTimestamps
{
    use WithCreatedTimestamp;
    use WithUpdatedTimestamp;

}