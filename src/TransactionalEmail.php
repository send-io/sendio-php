<?php

namespace Sendio;

/**
 * A transactional email: a named, saved send of a published template (a
 * sign-in code, a password reset, a receipt). Send it with
 * `$sendio->emails->send(['transactional_id' => $tx->id, 'to' => ..., 'variables' => [...]])`.
 *
 * @property string $object The type of object, `transactional_email`.
 * @property string $id The transactional email ID (`tx_` and 27 letters and digits).
 * @property string $name Its name.
 * @property string $template_id The template whose published version is its content.
 * @property string $template_name The template's name.
 * @property string|null $template_alias The template's alias.
 * @property bool $template_deleted Whether the template was deleted (sends then fail).
 * @property string|null $published_version_id The template version sends use now.
 * @property int|null $published_version_number That version's number.
 * @property string $from The sender.
 * @property string $subject The subject; it may use the template's variables.
 * @property string|null $preview_text The inbox preview text.
 * @property array<int, string> $reply_to The reply-to addresses.
 * @property bool $track_opens Open tracking (off by default).
 * @property bool $track_clicks Click tracking (off by default).
 * @property string $created_via `api` or `dashboard`.
 * @property string $created_at Time at which it was created.
 * @property string $updated_at Time at which it last changed; pass it as `expected_updated_at` to update safely.
 * @property array<int, array{key: string, type: string, fallback_value: mixed}>|null $variables The published version's variables (get only).
 * @property bool|null $deleted Set on the response of remove.
 */
class TransactionalEmail extends Resource
{
    //
}
