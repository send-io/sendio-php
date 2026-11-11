<?php

namespace Sendio\Service;

use Sendio\ValueObjects\Transporter\Payload;

class Template extends Service
{
    /**
     * Retrieve a template with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $id): \Sendio\Template
    {
        $payload = Payload::get('templates', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('templates', $result);
    }

    /**
     * Create a new template.
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters, array $options = []): \Sendio\Template
    {
        $payload = Payload::create('templates', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('templates', $result);
    }

    /**
     * List all templates.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Template>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('templates', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('templates', $result);
    }

    /**
     * Update a template with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $id, array $parameters): \Sendio\Template
    {
        $payload = Payload::update('templates', $id, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('templates', $result);
    }

    /**
     * Remove a template with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function remove(string $id): \Sendio\Template
    {
        $payload = Payload::delete('templates', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('templates', $result);
    }

    /**
     * Publish a template with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function publish(string $id): \Sendio\Template
    {
        $payload = Payload::publish('templates', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('templates', $result);
    }

    /**
     * Duplicate a template with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function duplicate(string $id): \Sendio\Template
    {
        $payload = Payload::duplicate('templates', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('templates', $result);
    }
}
