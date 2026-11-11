<?php

namespace Sendio\Service;

use Sendio\Contracts\Transporter;
use Sendio\Service\Contacts\Import;
use Sendio\Service\Contacts\Segment;
use Sendio\Service\Contacts\Topic;
use Sendio\ValueObjects\Transporter\Payload;

class Contact extends Service
{
    public Import $imports;

    public Segment $segments;

    public Topic $topics;

    /**
     * Create a new contact service instance with the given transport.
     */
    public function __construct(Transporter $transporter)
    {
        $this->imports = new Import($transporter);
        $this->segments = new Segment($transporter);
        $this->topics = new Topic($transporter);

        parent::__construct($transporter);
    }

    /**
     * Retrieve a single contact by ID or email.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $idOrEmail): \Sendio\Contact
    {
        $payload = Payload::get('contacts', $idOrEmail);

        $result = $this->transporter->request($payload);

        return $this->createResource('contacts', $result);
    }

    /**
     * Create a contact.
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters): \Sendio\Contact
    {
        $payload = Payload::create('contacts', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('contacts', $result);
    }

    /**
     * List all contacts.
     *
     * @param array{'segment_id'?: string, 'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Contact>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $segmentId = array_key_exists('segment_id', $options) ? $options['segment_id'] : null;

        $payload = $segmentId
            ? Payload::list("segments/{$segmentId}/contacts", $options)
            : Payload::list('contacts', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('contacts', $result);
    }

    /**
     * Update a contact by ID or email.
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $idOrEmail, array $parameters): \Sendio\Contact
    {
        $payload = Payload::update('contacts', $idOrEmail, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('contacts', $result);
    }

    /**
     * Remove a contact by ID or email.
     *
     * @see https://developer.send.io/docs/api
     */
    public function remove(string $idOrEmail): \Sendio\Contact
    {
        $payload = Payload::delete('contacts', $idOrEmail);

        $result = $this->transporter->request($payload);

        return $this->createResource('contacts', $result);
    }
}
