<?php

namespace Sendio\Service\Broadcasts;

use Sendio\Service\Service;
use Sendio\ValueObjects\Transporter\Payload;

class Version extends Service
{
    /**
     * List a broadcast's saved versions, newest first.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Broadcasts\Version>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(string $broadcastId, array $options = []): \Sendio\Collection
    {
        $payload = Payload::list("broadcasts/{$broadcastId}/versions", $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcast-versions', $result);
    }

    /**
     * Retrieve a single broadcast version by its version number or ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $broadcastId, int|string $version): \Sendio\Broadcasts\Version
    {
        $payload = Payload::get("broadcasts/{$broadcastId}/versions", (string) $version);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcast-versions', $result);
    }

    /**
     * Change a broadcast version's label. Only name and comment can change; its content is immutable.
     *
     * @param array{'name'?: string|null, 'comment'?: string|null} $parameters
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $broadcastId, int|string $version, array $parameters): \Sendio\Broadcasts\Version
    {
        $payload = Payload::update("broadcasts/{$broadcastId}/versions", (string) $version, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcast-versions', $result);
    }

    /**
     * Copy a version back into a draft or scheduled broadcast, saved as a new version.
     *
     * @see https://developer.send.io/docs/api
     */
    public function restore(string $broadcastId, int|string $version): \Sendio\Broadcasts\Version
    {
        $payload = Payload::withAction("broadcasts/{$broadcastId}/versions", (string) $version, 'restore');

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcast-versions', $result);
    }
}
