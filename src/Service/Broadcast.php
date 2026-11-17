<?php

namespace Sendio\Service;

use Sendio\Contracts\Transporter;
use Sendio\Service\Broadcasts\ClickedLink;
use Sendio\Service\Broadcasts\Version;
use Sendio\ValueObjects\Transporter\Payload;

class Broadcast extends Service
{
    public ClickedLink $clickedLinks;

    public Version $versions;

    /**
     * Create a new broadcast service instance with the given transport.
     */
    public function __construct(Transporter $transporter)
    {
        $this->clickedLinks = new ClickedLink($transporter);
        $this->versions = new Version($transporter);

        parent::__construct($transporter);
    }

    /**
     * Retrieve a single broadcast.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $id): \Sendio\Broadcast
    {
        $payload = Payload::get('broadcasts', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcasts', $result);
    }

    /**
     * Create a new broadcast to send to your audience.
     *
     * Without `send: true` this saves a draft, which needs no fields. With
     * `send: true`, from, subject, an html or text body and an audience
     * (segment_id, segment_ids or audience: all) are required.
     *
     * @param array{
     *     'name'?: string|null, 'from'?: string|null, 'subject'?: string|null, 'reply_to'?: string|string[],
     *     'html'?: string|null, 'text'?: string, 'preview_text'?: string|null, 'topic_id'?: string|null,
     *     'segment_id'?: string, 'audience_id'?: string, 'segment_ids'?: string[], 'exclude_segment_ids'?: string[],
     *     'audience'?: 'segments'|'all', 'unsubscribe_scope'?: 'all'|'list', 'tracking'?: bool,
     *     'template_engine'?: 'simple'|'handlebars', 'template_id'?: string, 'template_version_id'?: string,
     *     'send'?: bool, 'scheduled_at'?: string
     * } $parameters `audience_id` is deprecated: use `segment_id`.
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters): \Sendio\Broadcast
    {
        $payload = Payload::create('broadcasts', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcasts', $result);
    }

    /**
     * List all broadcasts.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string, 'status'?: string, 'segment_id'?: string, 'search'?: string} $options
     * @return \Sendio\Collection<\Sendio\Broadcast>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('broadcasts', $options, ['segment_id', 'search']);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcasts', $result);
    }

    /**
     * Retrieve the recipients of a broadcast for a given event type.
     *
     * @param array{'type': string, 'email'?: string, 'bounce_type'?: string, 'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Broadcasts\Recipient>
     *
     * @see https://developer.send.io/docs/api
     */
    public function recipients(string $id, array $options): \Sendio\Collection
    {
        $payload = Payload::list("broadcasts/{$id}/recipients", $options, ['type', 'email', 'bounce_type']);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcast-recipients', $result);
    }

    /**
     * Update a broadcast to send to your audience.
     *
     * Takes the create fields except send and scheduled_at; only the keys
     * given are changed. Once a broadcast is sending or sent, only its name
     * can change. `template_id` => null detaches a broadcast from its
     * template; `template_version_id` alone switches to another published
     * version of it. Sending html, text or template_engine detaches it too.
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $id, array $parameters): \Sendio\Broadcast
    {
        $payload = Payload::update('broadcasts', $id, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcasts', $result);
    }

    /**
     * Start sending broadcasts to your audience.
     *
     * @param array{'scheduled_at'?: string} $parameters
     *
     * @see https://developer.send.io/docs/api
     */
    public function send(string $broadcastId, array $parameters = []): \Sendio\Broadcast
    {
        $payload = Payload::create("broadcasts/{$broadcastId}/send", $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcasts', $result);
    }

    /**
     * Cancel a queued or scheduled broadcast.
     *
     * @see https://developer.send.io/docs/api
     */
    public function cancel(string $id): \Sendio\Broadcast
    {
        $payload = Payload::cancel('broadcasts', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcasts', $result);
    }

    /**
     * Duplicate a broadcast with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function duplicate(string $id): \Sendio\Broadcast
    {
        $payload = Payload::duplicate('broadcasts', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcasts', $result);
    }

    /**
     * Remove an existing broadcast.
     *
     * @see https://developer.send.io/docs/api
     */
    public function remove(string $id): \Sendio\Broadcast
    {
        $payload = Payload::delete('broadcasts', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcasts', $result);
    }

    /**
     * Estimate how many contacts an audience reaches now, before anything is saved.
     *
     * @param array{'segment_id'?: string, 'segment_ids'?: string[], 'exclude_segment_ids'?: string[], 'audience'?: 'segments'|'all', 'topic_id'?: string} $parameters
     *
     * @see https://developer.send.io/docs/api
     */
    public function estimate(array $parameters): \Sendio\Broadcasts\Estimate
    {
        $payload = Payload::create('broadcasts/estimate', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcast-estimates', $result);
    }

    /**
     * Send a test of a broadcast to 1 to 5 addresses (subject prefixed [Test]).
     *
     * @param string|string[] $to
     *
     * @see https://developer.send.io/docs/api
     */
    public function test(string $id, string|array $to): \Sendio\Resource
    {
        $payload = Payload::create("broadcasts/{$id}/test", ['to' => (array) $to]);

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcast-tests', $result);
    }

    /**
     * Retrieve a broadcast's delivery and engagement stats.
     *
     * @see https://developer.send.io/docs/api
     */
    public function stats(string $id): \Sendio\Broadcasts\Stats
    {
        $payload = Payload::get('broadcasts', "{$id}/stats");

        $result = $this->transporter->request($payload);

        return $this->createResource('broadcast-stats', $result);
    }
}
