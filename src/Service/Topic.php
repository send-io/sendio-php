<?php

namespace Sendio\Service;

use Sendio\ValueObjects\Transporter\Payload;

class Topic extends Service
{
    /**
     * Retrieve a topic with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $id): \Sendio\Topic
    {
        $payload = Payload::get('topics', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('topics', $result);
    }

    /**
     * Create a topic to segment your audience.
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters, array $options = []): \Sendio\Topic
    {
        $payload = Payload::create('topics', $parameters, $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('topics', $result);
    }

    /**
     * Retrieve a list of topics.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Topic>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('topics', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('topics', $result);
    }

    /**
     * Update an existing topic.
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $id, array $parameters): \Sendio\Topic
    {
        $payload = Payload::update('topics', $id, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('topics', $result);
    }

    /**
     * Remove an existing topic.
     *
     * @see https://developer.send.io/docs/api
     */
    public function remove(string $id): \Sendio\Topic
    {
        $payload = Payload::delete('topics', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('topics', $result);
    }
}
