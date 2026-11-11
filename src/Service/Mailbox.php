<?php

namespace Sendio\Service;

use Sendio\ValueObjects\Transporter\Payload;

class Mailbox extends Service
{
    /**
     * Retrieve a list of mailboxes.
     *
     * @return \Sendio\Collection<\Sendio\Mailbox>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(): \Sendio\Collection
    {
        $payload = Payload::list('mailboxes');

        $result = $this->transporter->request($payload);

        return $this->createResource('mailboxes', $result);
    }

    /**
     * Create a mailbox (an email account with IMAP/SMTP login).
     *
     * Pass a full `address`, or a `local_part` and `domain_id`. The domain must be
     * verified, and a 409 error is returned if the address already exists. For a
     * free mailbox without a domain, pass a `username` and optionally `org` and
     * `display_name`. When IMAP/SMTP is ready, the response includes `credentials`,
     * which are only shown once; the password is also an API key for the mailbox.
     *
     * @param array{
     *     address?: string,
     *     local_part?: string,
     *     domain_id?: string,
     *     username?: string,
     *     org?: string,
     *     display_name?: string
     * } $parameters
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters): \Sendio\Mailbox
    {
        $payload = Payload::create('mailboxes', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('mailboxes', $result);
    }

    /**
     * Update an existing mailbox.
     *
     * @param array{warmup_enabled: bool} $parameters
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $id, array $parameters): \Sendio\Mailbox
    {
        $payload = Payload::update('mailboxes', $id, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('mailboxes', $result);
    }

    /**
     * Reset the password of an existing mailbox.
     *
     * The old password stops working. The new password is also an API key for the mailbox.
     *
     * @see https://developer.send.io/docs/api
     */
    public function resetPassword(string $id): \Sendio\Mailboxes\Credentials
    {
        $payload = Payload::withAction('mailboxes', $id, 'password');

        $result = $this->transporter->request($payload);

        return $this->createResource('mailbox-credentials', $result);
    }

    /**
     * Remove an existing mailbox.
     *
     * This deletes the email account and any agent inbox on it.
     *
     * @see https://developer.send.io/docs/api
     */
    public function remove(string $id): \Sendio\Mailbox
    {
        $payload = Payload::delete('mailboxes', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('mailboxes', $result);
    }
}
