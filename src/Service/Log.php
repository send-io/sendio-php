<?php

namespace Sendio\Service;

use Sendio\ValueObjects\Transporter\Payload;

class Log extends Service
{
    /**
     * Retrieve a log with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $logId): \Sendio\Log
    {
        $payload = Payload::get('logs', $logId);

        $result = $this->transporter->request($payload);

        return $this->createResource('logs', $result);
    }

    /**
     * List all logs.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Log>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('logs', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('logs', $result);
    }
}
