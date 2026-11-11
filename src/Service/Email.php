<?php

namespace Sendio\Service;

use InvalidArgumentException;
use Sendio\Contracts\Transporter;
use Sendio\Service\Emails\Attachment;
use Sendio\Service\Emails\Receiving;
use Sendio\ValueObjects\Transporter\Payload;

class Email extends Service
{
    public Attachment $attachments;

    public Receiving $receiving;

    /**
     * Create a new email service instance with the given transport.
     */
    public function __construct(Transporter $transporter)
    {
        $this->attachments = new Attachment($transporter);
        $this->receiving = new Receiving($transporter);

        parent::__construct($transporter);
    }

    /**
     * Retrieve an email with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $id): \Sendio\Email
    {
        $payload = Payload::get('emails', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('emails', $result);
    }

    /**
     * Send an email with the given parameters.
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters, array $options = []): \Sendio\Email
    {
        $payload = Payload::create('emails', $parameters, $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('emails', $result);
    }

    /**
     * Send an email with the given parameters.
     *
     * @see https://developer.send.io/docs/api
     */
    public function send(array $parameters, array $options = []): \Sendio\Email
    {
        return $this->create($parameters, $options);
    }

    /**
     * List all emails.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('emails', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('emails', $result);
    }

    /**
     * Update a scheduled email with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $id, array $parameters): \Sendio\Email
    {
        $payload = Payload::update('emails', $id, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('emails', $result);
    }

    /**
     * Cancel a scheduled email with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function cancel(string $id): \Sendio\Email
    {
        $payload = Payload::cancel('emails', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('emails', $result);
    }

    /**
     * Create a shareable link for a sent or received email with the given ID.
     *
     * @param array{'expires_in'?: string} $parameters
     *
     * @see https://developer.send.io/docs/api
     */
    public function share(string $id, array $parameters = []): \Sendio\Email
    {
        $payload = Payload::create("emails/{$id}/share", $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('emails', $result);
    }

    /**
     * Retrieve email delivery and engagement metrics.
     *
     * @param array{
     *     'start_date'?: string,
     *     'end_date'?: string,
     *     'timezone'?: string,
     *     'granularity'?: 'hourly'|'daily'|'weekly'|'monthly',
     *     'metrics'?: array<int, 'received'|'delivered'|'complained'|'suppressed'|'bounced'|'bounced_transient'|'bounced_permanent'|'bounced_undetermined'|'opened'|'clicked'|'unsubscribed'|'delivery_delayed'|'failed'|'sent'|'unique_opened'|'unique_clicked'|'delivery_rate'|'open_rate'|'click_rate'|'bounce_rate'|'complaint_rate'|'unsubscribe_rate'>,
     *     'dimensions'?: array<int, 'period'|'domain'|'email'|'broadcast'>,
     *     'domain_id'?: array<int, string>,
     *     'email_id'?: array<int, string>,
     *     'broadcast_id'?: array<int, string>
     * } $options
     *
     * @see https://developer.send.io/docs/api
     */
    public function metrics(array $options = []): \Sendio\Metrics
    {
        $dimensions = $options['dimensions'] ?? [];
        $hasEmail = in_array('email', $dimensions, true) || ! empty($options['email_id']);
        $hasBroadcast = in_array('broadcast', $dimensions, true) || ! empty($options['broadcast_id']);

        if ($hasEmail && $hasBroadcast) {
            throw new InvalidArgumentException('The `broadcast` dimension/`broadcast_id` filter cannot be combined with the `email` dimension/`email_id` filter.');
        }

        $queryParams = [];

        foreach (['start_date', 'end_date', 'timezone', 'granularity'] as $key) {
            if (isset($options[$key]) && $options[$key] !== '') {
                $queryParams[$key] = $options[$key];
            }
        }

        foreach (['metrics', 'dimensions', 'domain_id', 'email_id', 'broadcast_id'] as $key) {
            if (! empty($options[$key])) {
                $queryParams[$key] = implode(',', $options[$key]);
            }
        }

        $resource = 'emails/metrics' . ($queryParams !== [] ? '?' . http_build_query($queryParams) : '');

        $payload = Payload::list($resource);

        $result = $this->transporter->request($payload);

        return $this->createResource('emails-metrics', $result, asList: false);
    }
}
