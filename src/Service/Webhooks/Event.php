<?php

namespace Sendio\Service\Webhooks;

use Sendio\Contracts\Transporter;
use Sendio\Service\Service;
use Sendio\ValueObjects\Transporter\Payload;

class Event extends Service
{
    public EventAttempt $attempts;

    public function __construct(Transporter $transporter)
    {
        $this->attempts = new EventAttempt($transporter);

        parent::__construct($transporter);
    }

    /**
     * Retrieve a list of events delivered to a webhook.
     *
     * @param array{'limit'?: int, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Webhooks\Event>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(string $webhookId, array $options = []): \Sendio\Collection
    {
        $options = array_intersect_key($options, array_flip(['limit', 'after']));
        $payload = Payload::list("webhooks/{$webhookId}/events", $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('webhook-events', $result);
    }

    /**
     * Retrieve a single event delivered to a webhook.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $webhookId, string $eventId): \Sendio\Webhooks\Event
    {
        $payload = Payload::get("webhooks/{$webhookId}/events", $eventId);

        $result = $this->transporter->request($payload);

        return $this->createResource('webhook-events', $result);
    }

    /**
     * Replay a single event delivered to a webhook.
     *
     * @see https://developer.send.io/docs/api
     */
    public function replay(string $webhookId, string $eventId): \Sendio\Webhooks\Event
    {
        $payload = Payload::withAction("webhooks/{$webhookId}/events", $eventId, 'replay');

        $result = $this->transporter->request($payload);

        return $this->createResource('webhook-events', $result);
    }
}
