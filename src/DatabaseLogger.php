<?php
namespace Pyncer\Log;

use Pyncer\Log\AbstractLogger;
use Pyncer\Database\ConnectionInterface;
use Stringable;

use function json_encode;

class DatabaseLogger extends AbstractLogger
{
    protected ConnectionInterface $connection;
    protected string $table;

    public function __construct(ConnectionInterface $connection, string $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    public function log(
        mixed $level,
        string|Stringable $message,
        array $context = []
    ): void
    {
        $context = ($context ? json_encode($context) : null);

        $query = $this->connection
            ->insert($this->table)
            ->values([
                'level' => $level,
                'message' => strval($message),
                'context' => $context,
                'insert_date_time' => $this->connection->dateTime()
            ])->execute();
    }

    public function commit(): void
    {}
}
