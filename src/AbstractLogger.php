<?php
namespace Pyncer\Log;

use Psr\Log\LoggerInterface as PsrLoggerInterface;
use Psr\Log\LoggerTrait as PsrLoggerTrait;
use Stringable;

abstract class AbstractLogger implements PsrLoggerInterface {
    use PsrLoggerTrait;

    public function __destruct() {
        $this->commit();
    }

    abstract public function log(
        mixed $level,
        string|Stringable $message,
        array $context = []
    ): void;

    public function commit(): void
    {}

    public function inherit(PsrLoggerInterface $logger): bool
    {
        if (!($logger instanceof InheritableLoggerInterface)) {
            return false;
        }

        foreach ($logger->getItems() as $item) {
            $this->log(
                $item['level'],
                $item['message'],
                $item['value']
            );
        }

        return true;
    }
}
