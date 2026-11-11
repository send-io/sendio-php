<?php

namespace Sendio\Service;

use InvalidArgumentException;
use Sendio\ValueObjects\Transporter\Payload;

class Event extends Service
{
    /**
     * Retrieve a single event by ID or name.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $identifier): \Sendio\Event
    {
        $payload = Payload::get('events', $identifier);

        $result = $this->transporter->request($payload);

        return $this->createResource('events', $result);
    }

    /**
     * Create a new event that can be used to trigger automations.
     *
     * @param array{name: string, schema?: array<string, 'string'|'number'|'boolean'|'date'>|null} $parameters
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters): \Sendio\Event
    {
        $payload = Payload::create('events', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('events', $result);
    }

    /**
     * List all events.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Event>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('events', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('events', $result);
    }

    /**
     * Update an existing event schema.
     *
     * @param array{schema: array<string, 'string'|'number'|'boolean'|'date'>|null} $parameters
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $identifier, array $parameters): \Sendio\Event
    {
        $payload = Payload::update('events', $identifier, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('events', $result);
    }

    /**
     * Remove an existing event.
     *
     * @see https://developer.send.io/docs/api
     */
    public function remove(string $identifier): \Sendio\Event
    {
        $payload = Payload::delete('events', $identifier);

        $result = $this->transporter->request($payload);

        return $this->createResource('events', $result);
    }

    /**
     * Send a named event to trigger matching automations.
     *
     * @param array{event: string, email?: string, contact_id?: string, payload?: array|object} $parameters
     *
     * @see https://developer.send.io/docs/api
     */
    public function send(array $parameters): \Sendio\Event
    {
        $hasEmail = array_key_exists('email', $parameters) && is_string($parameters['email']) && trim((string) $parameters['email']) !== '';
        $hasContactId = array_key_exists('contact_id', $parameters) && is_string($parameters['contact_id']) && trim((string) $parameters['contact_id']) !== '';

        if ($hasEmail === $hasContactId) {
            throw new InvalidArgumentException('Either contact_id or email must be provided, but not both.');
        }

        $payload = Payload::create('events/send', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('events', $result);
    }
}
