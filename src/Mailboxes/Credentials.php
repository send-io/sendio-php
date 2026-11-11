<?php

namespace Sendio\Mailboxes;

use Sendio\Resource;

/**
 * @property string $object The type of object.
 * @property string $id The unique identifier for the mailbox.
 * @property string $address The full email address of the mailbox.
 * @property array{username: string, password: string, imap: array{host: string, port: int, security: string}, smtp: array{host: string, port: int, security: string}} $credentials The new IMAP/SMTP login. The password is also an API key for this mailbox.
 * @property string $note A note about the new credentials.
 */
class Credentials extends Resource
{
    //
}
