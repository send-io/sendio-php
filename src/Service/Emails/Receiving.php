<?php

namespace Sendio\Service\Emails;

use Sendio\Contracts\Transporter;
use Sendio\Service\Emails\Receiving\Attachment;
use Sendio\Service\Service;
use Sendio\ValueObjects\Transporter\Payload;

class Receiving extends Service
{
    public Attachment $attachments;

    /**
     * Create a new email receiving service instance with the given transport.
     */
    public function __construct(Transporter $transporter)
    {
        $this->attachments = new Attachment($transporter);

        parent::__construct($transporter);
    }

    /**
     * Retrieve an inbound email with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $id): \Sendio\Emails\Receiving
    {
        $payload = Payload::get('emails/receiving', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('receiving', $result);
    }

    /**
     * List all inbound emails.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Emails\Receiving>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('emails/receiving', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('receiving', $result);
    }
}
