<?php

namespace Sendio\Service;

use Sendio\ValueObjects\Transporter\Payload;

class Segment extends Service
{
    /**
     * Retrieve a segment with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $id): \Sendio\Segment
    {
        $payload = Payload::get('segments', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('segments', $result);
    }

    /**
     * Create a new segment.
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters): \Sendio\Segment
    {
        $payload = Payload::create('segments', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('segments', $result);
    }

    /**
     * List all segments.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Segment>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('segments', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('segments', $result);
    }

    /**
     * Update a segment with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $id, array $parameters): \Sendio\Segment
    {
        $payload = Payload::update('segments', $id, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('segments', $result);
    }

    /**
     * Remove a segment with the given ID.
     *
     * @see https://developer.send.io/docs/api#path-parameters
     */
    public function remove(string $id): \Sendio\Segment
    {
        $payload = Payload::delete('segments', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('segments', $result);
    }
}
