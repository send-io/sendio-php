<?php

namespace Sendio\Service;

use Sendio\ValueObjects\Transporter\Payload;

/**
 * Transactional emails (`/transactional`): saved sends of a published template,
 * sent with `$sendio->emails->send(['transactional_id' => ..., 'to' => ..., 'variables' => [...]])`.
 * Creating, changing and deleting them needs a full-access API key; a
 * sending-only key can send them.
 */
class Transactional extends Service
{
    /**
     * Save a transactional email on a published template.
     *
     * @param array{
     *     name: string,
     *     template_id: string,
     *     from: string,
     *     subject: string,
     *     preview_text?: string|null,
     *     reply_to?: string|array<int, string>,
     *     track_opens?: bool,
     *     track_clicks?: bool
     * } $parameters
     * @param array{idempotency_key?: string} $options
     *
     * @see https://developer.send.io/docs/api#create-transactional-email
     */
    public function create(array $parameters, array $options = []): \Sendio\TransactionalEmail
    {
        $payload = Payload::create('transactional', $parameters, $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('transactional', $result);
    }

    /**
     * List transactional emails, newest first.
     *
     * @param array{limit?: int, after?: string, before?: string, search?: string, template_id?: string} $options
     * @return \Sendio\Collection<\Sendio\TransactionalEmail>
     *
     * @see https://developer.send.io/docs/api#list-transactional-emails
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('transactional', $options, ['search', 'template_id']);

        $result = $this->transporter->request($payload);

        return $this->createResource('transactional', $result);
    }

    /**
     * Retrieve one, with the variables of the template version its sends use now.
     *
     * @see https://developer.send.io/docs/api#retrieve-transactional-email
     */
    public function get(string $id): \Sendio\TransactionalEmail
    {
        $payload = Payload::get('transactional', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('transactional', $result);
    }

    /**
     * Change the fields given; the others are kept. Sends use the change
     * within 10 seconds. Pass `expected_updated_at` (the `updated_at` you
     * read) to get a 409 conflict instead of overwriting a change made since.
     *
     * @param array{
     *     name?: string,
     *     template_id?: string,
     *     from?: string,
     *     subject?: string,
     *     preview_text?: string|null,
     *     reply_to?: string|array<int, string>|null,
     *     track_opens?: bool,
     *     track_clicks?: bool,
     *     expected_updated_at?: string
     * } $parameters
     *
     * @see https://developer.send.io/docs/api#update-transactional-email
     */
    public function update(string $id, array $parameters): \Sendio\TransactionalEmail
    {
        $payload = Payload::update('transactional', $id, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('transactional', $result);
    }

    /**
     * Delete it: sends of it then fail with 404. Its emails and stats stay.
     *
     * @see https://developer.send.io/docs/api#delete-transactional-email
     */
    public function remove(string $id): \Sendio\TransactionalEmail
    {
        $payload = Payload::delete('transactional', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('transactional', $result);
    }

    /**
     * Send it now to 1 to 5 addresses, with "[Test] " before the subject.
     *
     * @param string|array<int, string> $to
     * @param array<string, mixed> $variables
     *
     * @see https://developer.send.io/docs/api#test-transactional-email
     */
    public function test(string $id, string|array $to, array $variables = []): \Sendio\Email
    {
        $parameters = ['to' => (array) $to];
        if ($variables !== []) {
            $parameters['variables'] = $variables;
        }

        $payload = Payload::create("transactional/{$id}/test", $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('emails', $result);
    }

    /**
     * Its sends' numbers in a period (the template stats' shape).
     *
     * @param array{from?: string, to?: string, timezone?: string, version?: string|int, granularity?: string} $options
     *
     * @see https://developer.send.io/docs/api#transactional-email-stats
     */
    public function stats(string $id, array $options = []): \Sendio\Resource
    {
        $payload = Payload::list("transactional/{$id}/stats", $options, ['from', 'to', 'timezone', 'version', 'granularity']);

        $result = $this->transporter->request($payload);

        return $this->createResource('transactional-stats', $result, false);
    }
}
