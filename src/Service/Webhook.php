<?php

namespace Sendio\Service;

use Sendio\Contracts\Transporter;
use Sendio\Service\Webhooks\Event;
use Sendio\ValueObjects\Transporter\Payload;
use Sendio\WebhookSignature;

class Webhook extends Service
{
    public Event $events;

    public function __construct(Transporter $transporter)
    {
        $this->events = new Event($transporter);

        parent::__construct($transporter);
    }

    /**
     * Retrieve a webhook with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $id): \Sendio\Webhook
    {
        $payload = Payload::get('webhooks', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('webhooks', $result);
    }

    /**
     * Create a new webhook.
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters): \Sendio\Webhook
    {
        $payload = Payload::create('webhooks', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('webhooks', $result);
    }

    /**
     * List all webhooks.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Webhook>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('webhooks', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('webhooks', $result);
    }

    /**
     * Update a webhook with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $id, array $parameters): \Sendio\Webhook
    {
        $payload = Payload::update('webhooks', $id, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('webhooks', $result);
    }

    /**
     * Remove a webhook with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function remove(string $id): \Sendio\Webhook
    {
        $payload = Payload::delete('webhooks', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('webhooks', $result);
    }

    /**
     * Rotate the signing secret of a webhook with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function rotateSigningSecret(string $id): \Sendio\Webhook
    {
        $payload = Payload::withAction('webhooks', $id, 'signing-secret/rotate');

        $result = $this->transporter->request($payload);

        return $this->createResource('webhooks', $result);
    }

    /**
     * Determine if the incoming webhook request is valid.
     */
    public function verify(string $payload, array $headers, string $secret, ?int $tolerance = 300): bool
    {
        return WebhookSignature::verify($payload, $headers, $secret, $tolerance);
    }
}
