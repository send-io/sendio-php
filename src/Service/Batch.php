<?php

namespace Sendio\Service;

use Sendio\ValueObjects\Transporter\Payload;

class Batch extends Service
{
    /**
     * Send a batch of emails with the given parameters.
     *
     * @param array{'idempotency_key'?: string, 'batch_validation'?: string} $options
     * @return \Sendio\Collection<\Sendio\Email>
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters, array $options = []): \Sendio\Collection
    {
        $payload = Payload::create('emails/batch', $parameters, $options);

        $payload->withHeader('x-batch-validation', $options['batch_validation'] ?? 'strict');

        $result = $this->transporter->request($payload);

        return $this->createResource('emails', $result);
    }

    /**
     * Send a batch of emails with the given parameters.
     *
     * @param array{'idempotency_key'?: string, 'batch_validation'?: string} $options
     * @return \Sendio\Collection<\Sendio\Email>
     *
     * @see https://developer.send.io/docs/api
     */
    public function send(array $parameters, array $options = []): \Sendio\Collection
    {
        return $this->create($parameters, $options);
    }
}
