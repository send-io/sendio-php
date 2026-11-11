<?php

namespace Sendio\Service\Emails\Receiving;

use Sendio\Service\Service;
use Sendio\ValueObjects\Transporter\Payload;

class Attachment extends Service
{
    /**
     * Retrieve an attachment for an inbound email.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $emailId, string $id): \Sendio\Emails\Attachment
    {
        $payload = Payload::get("emails/receiving/$emailId/attachments", $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('attachments', $result);
    }

    /**
     * List all attachments and their contents for the given ID.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Emails\Attachment>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(string $emailId, array $options = []): \Sendio\Collection
    {
        $payload = Payload::list("emails/receiving/$emailId/attachments", $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('attachments', $result);
    }
}
