<?php

namespace Sendio\Service;

use Sendio\ValueObjects\Transporter\Payload;

class ContactProperty extends Service
{
    /**
     * Retrieve a single contact property.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $id): \Sendio\ContactProperty
    {
        $payload = Payload::get('contact-properties', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('contact-properties', $result);
    }

    /**
     * Create a new contact property.
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters): \Sendio\ContactProperty
    {
        $payload = Payload::create('contact-properties', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('contact-properties', $result);
    }

    /**
     * List all contact properties.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\ContactProperty>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('contact-properties', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('contact-properties', $result);
    }

    /**
     * Update a contact property with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $id, array $parameters): \Sendio\ContactProperty
    {
        $payload = Payload::update('contact-properties', $id, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('contact-properties', $result);
    }

    /**
     * Remove a contact property with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function remove(string $id): \Sendio\ContactProperty
    {
        $payload = Payload::delete('contact-properties', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('contact-properties', $result);
    }
}
