<?php

namespace Sendio;

/**
 * @property string $object The type of object.
 * @property string $id The unique identifier for the mailbox.
 * @property string $address The full email address of the mailbox.
 * @property string $kind The kind of mailbox. Possible values: `normal` or `agent`.
 * @property string|null $agent_inbox_id The unique identifier of the agent inbox on this mailbox, if any.
 * @property string $imap_smtp The IMAP/SMTP login status. Possible values: `ready`, `pending`, `retrying` or `unavailable`.
 * @property string|null $domain_id The unique identifier of the domain the mailbox belongs to.
 * @property string|null $domain The name of the domain the mailbox belongs to.
 * @property bool $warmup_enabled Whether warmup is enabled for the mailbox.
 * @property string|null $warmup_changed_at Time at which warmup was last turned on or off.
 * @property string $created_at Time at which the mailbox was created.
 * @property int $sent The number of emails sent in the last 30 days.
 * @property int $sent_total The total number of emails sent.
 * @property int $delivered The number of emails delivered in the last 30 days.
 * @property int $bounced The number of emails bounced in the last 30 days.
 * @property int $complained The number of spam complaints in the last 30 days.
 * @property string|null $last_sent_at Time at which the mailbox last sent an email.
 * @property string $health The sending health of the mailbox. Possible values: `no_data`, `healthy`, `at_risk` or `poor`.
 * @property array{username: string, password: string, imap: array{host: string, port: int, security: string}, smtp: array{host: string, port: int, security: string}}|null $credentials The IMAP/SMTP login, only returned once on create when IMAP/SMTP is ready. The password is also an API key for this mailbox.
 */
class Mailbox extends Resource
{
    //
}
