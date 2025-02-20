<?php
declare(strict_types=1);

namespace App\Logging;

use Monolog\LogRecord;

class SanitizeSensitiveData
{
    private array $sensitiveKeys = ['token', 'password', 'api_key', 'authorization', 'secret'];

    public function __invoke(LogRecord $record): LogRecord
    {
        return $record->with(
            context: $this->sanitizeArray($record->context),
            extra: $this->sanitizeArray($record->extra)
        );
    }

    protected function sanitizeArray(array $data): array
    {
        if (isset($data['target']) && in_array($data['target'], ['Login', 'Register'])) {
            $data['arguments'] = '***';
        }

        if (isset($data['arguments'][0]['token'])) {
            $data['arguments'][0]['token'] = '***';
        }

        foreach ($this->sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '***';
            }
        }

        return $data;
    }
}
