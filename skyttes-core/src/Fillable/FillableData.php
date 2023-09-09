<?php declare(strict_types=1);

namespace Skyttes\Core\Fillable;

/**
 * @template T
 */
abstract class FillableData
{
    abstract public function isEmpty(): bool;

    /**
     * @param T $values
     */
    abstract public static function fill(mixed $values): self;

    public function canCreate(): bool {
        return true;
    }

    public function canUpdate(): bool {
        return true;
    }
}