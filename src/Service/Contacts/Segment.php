<?php

namespace Sendio\Service\Contacts;

use Sendio\Service\Service;
use Sendio\ValueObjects\Transporter\Payload;

class Segment extends Service
{
    public function add(string $contact, string $segmentId): \Sendio\Segment
    {
        $payload = Payload::create("contacts/$contact/segments/$segmentId", []);

        $result = $this->transporter->request($payload);

        return $this->createResource('segments', $result);
    }

    /**
     * Retrieve a list of segments for the given contact ID.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Segment>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(string $contactId, array $options = []): \Sendio\Collection
    {
        $payload = Payload::list("contacts/$contactId/segments", $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('segments', $result);
    }

    public function remove(string $contact, string $segmentId): \Sendio\Segment
    {
        $payload = Payload::delete("contacts/$contact/segments", $segmentId);

        $result = $this->transporter->request($payload);

        return $this->createResource('segments', $result);
    }
}
