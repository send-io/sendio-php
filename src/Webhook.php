<?php

namespace Sendio;

/**
 * @property string $object The type of object.
 * @property string $id The unique identifier for the webhook.
 * @property string $created_at Time at which the webhook was created, RFC 3339 in UTC (for example 2026-11-10T14:40:35.840078+00:00).
 * @property string $status The current status of the webhook.
 * @property string $endpoint The URL where webhook events will be sent.
 * @property array<int, string> $events The array of event types the webhook is subscribed to.
 * @property string $signing_secret The signing secret used to verify this webhook. Left out for workspace Members.
 * @property bool $signing_secret_hidden True when the signing secret is left out because the caller is a workspace Member.
 * @property string|null $disabled_reason Why send.io disabled the endpoint (after 3 days of failed deliveries).
 * @property string|null $failing_since When the current run of failed deliveries started, RFC 3339 in UTC.
 */
class Webhook extends Resource
{
    //
}
