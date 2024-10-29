<?php declare(strict_types=1);

namespace Skyttes\Application;

final class Params
{
    private bool $locked = false;

    public function __construct(
        private array $data = [],
    )
    {
    }

    public function lock(): void
    {
        if (!$this->locked) {
            $this->locked = true;
        }
    }

    public function __get(string $name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }

    public function __set(string $name, $value): void
    {
        if ($this->locked) {
            trigger_error(sprintf("Skyttes\Application: Trying to set value of param '%s' after Kernel initialization", $name));
            return;
        }

        $this->data[$name] = $value;
    }

}