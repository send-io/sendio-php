<?php

namespace Sendio\Service\Webhooks;

use Sendio\Service\Service;
use Sendio\ValueObjects\Transporter\Payload;

class EventAttempt extends Service
{
    /**
     * Retrieve the delivery attempts for a webhook event.
     *
     * @param array{'limit'?: int, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Webhooks\EventAttempt>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(string $webhookId, string $eventId, array $options = []): \Sendio\Collection
    {
        $options = array_intersect_key($options, array_flip(['limit', 'after']));
        $payload = Payload::list("webhooks/{$webhookId}/events/{$eventId}/attempts", $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('webhook-event-attempts', $result);
    }
}
