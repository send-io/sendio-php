<?php

namespace Sendio;

/**
 * @property string $object The type of object, `broadcast`.
 * @property string $id The unique identifier for the broadcast.
 * @property null|string $name The broadcast's name.
 * @property null|string $segment_id The broadcast's first segment.
 * @property null|string $audience_id Deprecated: use `segment_id`.
 * @property array<int, string> $segment_ids The segments the broadcast goes to.
 * @property array<int, string> $exclude_segment_ids Segments whose members never get the broadcast.
 * @property string $audience `segments` (the segments' subscribed members) or `all` (every contact).
 * @property string $unsubscribe_scope `all` or `list` (leave the broadcast's segments only).
 * @property null|string $from The sender's email address.
 * @property null|string $subject The email subject.
 * @property null|array $reply_to The reply-to email addresses.
 * @property null|string $preview_text The preview text.
 * @property null|string $html The HTML body.
 * @property null|string $text The plain text body.
 * @property string $status The broadcast's status.
 * @property string $created_at Time at which the broadcast was created.
 * @property null|string $scheduled_at Time at which the broadcast is scheduled to send.
 * @property null|string $sent_at Time at which the broadcast was sent.
 * @property null|string $topic_id The topic recipients must be opted in to.
 * @property null|array $design The editor design.
 * @property bool $tracking Whether opens and clicks are tracked.
 * @property string $template_engine `simple` or `handlebars`.
 * @property null|string $failed_reason Why the broadcast failed, if it did.
 * @property string $updated_at Time at which the broadcast was updated.
 * @property null|int $version_number The broadcast's current version number.
 */
class Broadcast extends Resource
{
    //
}
