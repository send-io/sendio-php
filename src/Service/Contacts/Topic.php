<?php

namespace Sendio\Service\Contacts;

use Sendio\Service\Service;
use Sendio\ValueObjects\Transporter\Payload;

class Topic extends Service
{
    /**
     * Retrieve a list of topics subscriptions for a contact.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Contacts\Topic>
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $contactId): \Sendio\Collection
    {
        $payload = Payload::list("contacts/$contactId/topics");

        $result = $this->transporter->request($payload);

        return $this->createResource('contact-topics', $result);
    }

    /**
     * Update topic subscriptions for a contact.
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $contactId, array $parameters): \Sendio\Contact
    {
        $payload = Payload::update('contacts', "$contactId/topics", $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('contacts', $result);
    }
}
