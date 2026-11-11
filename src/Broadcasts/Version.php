<?php

namespace Sendio\Broadcasts;

use Sendio\Resource;

/**
 * @property string $object The type of object, `broadcast_version`.
 * @property string $id The unique identifier for the version.
 * @property string $broadcast_id The broadcast this version belongs to.
 * @property int $version_number The version's number, counting from 1.
 * @property null|string $name The version's label.
 * @property null|string $comment The version's comment.
 * @property null|string $broadcast_name The broadcast's name when this version was saved.
 * @property null|string $subject The subject saved in this version.
 * @property null|int $restored_from The version number this one was restored from, if any.
 * @property string $created_at Time at which the version was saved.
 * @property bool $current Whether this is the broadcast's current version.
 * @property null|string $from The sender saved in this version. Only present on a single version.
 * @property null|array $reply_to The reply-to addresses. Only present on a single version.
 * @property null|string $preview_text The preview text. Only present on a single version.
 * @property null|string $html The HTML body. Only present on a single version.
 * @property null|string $text The plain text body. Only present on a single version.
 * @property null|array $design The editor design. Only present on a single version.
 * @property string $template_engine `simple` or `handlebars`. Only present on a single version.
 * @property bool $tracking Whether opens and clicks are tracked. Only present on a single version.
 * @property null|string $topic_id The topic. Only present on a single version.
 * @property array<int, string> $segment_ids The segments. Only present on a single version.
 * @property array<int, string> $exclude_segment_ids The excluded segments. Only present on a single version.
 * @property string $audience `segments` or `all`. Only present on a single version.
 * @property string $unsubscribe_scope `all` or `list`. Only present on a single version.
 */
class Version extends Resource
{
    //
}
