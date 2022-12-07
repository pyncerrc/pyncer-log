<?php
namespace Pyncer\Log;

use Pyncer\Log\AbstractLogger;
use Pyncer\Data\DataRewriterInterface;
use Pyncer\Data\Mapper\MapperAdaptorInterface;
use Pyncer\Database\ConnectionInterface;
use Stringable;

use function json_encode;
use function Pyncer\date_time as pyncer_date_time;
use function strval;

class DatabaseLogger extends AbstractLogger
{
    public function __construct(
        protected MapperAdaptorInterface $mapperAdaptor,
    ) {}

    public function log(
        mixed $level,
        string|Stringable $message,
        array $context = []
    ): void
    {
        $context = ($context ? json_encode($context) : null);

        $data = [
            'level' => $level,
            'message' => strval($message),
            'context' => $context,
            'insert_date_time' => pyncer_date_time()
        ];

        $model = $this->mapperAdaptor->forgeModel($data);
        $this->mapperAdaptor->getMapper()->insert($model);
    }

    public function commit(): void
    {}
}
