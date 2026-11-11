<?php

namespace Sendio\Service;

use Sendio\ValueObjects\Transporter\Payload;

class Audience extends Service
{
    /**
     * Retrieve a single audience by the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $id): \Sendio\Audience
    {
        $payload = Payload::get('audiences', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('audiences', $result);
    }

    /**
     * Add a new audience.
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters): \Sendio\Audience
    {
        $payload = Payload::create('audiences', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('audiences', $result);
    }

    /**
     * List all audiences.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Audience>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('audiences', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('audiences', $result);
    }

    /**
     * Remove a audience with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function remove(string $id): \Sendio\Audience
    {
        $payload = Payload::delete('audiences', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('audiences', $result);
    }
}
