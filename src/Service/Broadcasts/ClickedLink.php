<?php

namespace Sendio\Service\Broadcasts;

use Sendio\Service\Service;
use Sendio\ValueObjects\Transporter\Payload;

class ClickedLink extends Service
{
    /**
     * Retrieve a broadcast's clicked links.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Broadcasts\ClickedLink>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(string $broadcastId, array $options = []): \Sendio\Collection
    {
        $payload = Payload::list("broadcasts/$broadcastId/clicked-links", $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcast-clicked-links', $result);
    }
}
