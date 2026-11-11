<?php

namespace Sendio\Service\Emails;

use Sendio\Service\Service;
use Sendio\ValueObjects\Transporter\Payload;

class Attachment extends Service
{
    /**
     * Retrieve a single attachment from a sent email.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $emailId, string $id): \Sendio\Emails\Attachment
    {
        $payload = Payload::get("emails/$emailId/attachments", $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('attachments', $result);
    }

    /**
     * Retrieve a list of email attachments and their contents for the given email ID.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Emails\Attachment>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(string $emailId, array $options = []): \Sendio\Collection
    {
        $payload = Payload::list("emails/$emailId/attachments", $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('attachments', $result);
    }
}
