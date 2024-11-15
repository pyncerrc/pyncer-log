<?php
namespace Pyncer\Log;

use Psr\Log\LoggerInterface as PsrLoggerInterface;
use Pyncer\Log\AbstractLogger;
use Pyncer\Log\InheritableLoggerInterface;
use Pyncer\Log\TemporaryLogger;
use Stringable;

class GroupLogger extends AbstractLogger implements InheritableLoggerInterface
{
    private TemporaryLogger $temporaryLogger;
    private array $loggers;

    public function __construct(
        PsrLoggerInterface ...$loggers,
    ) {
        $this->loggers = $loggers;
        $this->temporaryLogger = new TemporaryLogger();
    }

    public function addLogger(PsrLoggerInterface $logger): static
    {
        $logger->inherit($this->temporaryLogger);
        $this->loggers[] = $logger;
        return $this;
    }

    public function log(
        mixed $level,
        string|Stringable $message,
        array $context = []
    ): void
    {
        $this->temporaryLogger->log($level, $message, $context);
        foreach ($this->loggers as $logger) {
            $logger->log($level, $message, $context);
        }
    }

    public function commit(): void
    {
        $this->temporaryLogger->commit();
        foreach ($this->loggers as $logger) {
            $logger->commit();
        }
    }

    public function getItems(): array
    {
        return $this->temporaryLogger->getItems();
    }
}
