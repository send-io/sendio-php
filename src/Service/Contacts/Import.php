<?php

namespace Sendio\Service\Contacts;

use Sendio\Service\Service;
use Sendio\ValueObjects\Transporter\Payload;

class Import extends Service
{
    /**
     * Retrieve a single contact import.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $id): \Sendio\Contacts\Import
    {
        $payload = Payload::get('contacts/imports', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('contact-imports', $result);
    }

    /**
     * Create a contact import.
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters): \Sendio\Contacts\Import
    {
        $payload = Payload::upload('contacts/imports', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('contact-imports', $result);
    }

    /**
     * List all contact imports.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string, 'status'?: string} $options
     * @return \Sendio\Collection<\Sendio\Contacts\Import>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('contacts/imports', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('contact-imports', $result);
    }
}
