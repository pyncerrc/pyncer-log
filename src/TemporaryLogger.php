<?php
namespace Pyncer\Log;

use Pyncer\Log\AbstractLogger;
use Pyncer\Log\InheritableLoggerInterface;
use Stringable;

class TemporaryLogger extends AbstractLogger implements
    InheritableLoggerInterface
{
    protected array $items = [];

    public function log(
        mixed $level,
        string|Stringable $message,
        array $context = []
    ): void
    {
        $this->items[] = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function commit(): void
    {
        $this->items = [];
    }
}
