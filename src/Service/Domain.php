<?php

namespace Sendio\Service;

use Sendio\ValueObjects\Transporter\Payload;

class Domain extends Service
{
    /**
     * Retrieve a domain with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $id): \Sendio\Domain
    {
        $payload = Payload::get('domains', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('domains', $result);
    }

    /**
     * Add a new domain.
     *
     * Pass `capabilities` (Resend-compatible) to choose what the domain does:
     * `['capabilities' => ['receiving' => 'enabled']]` also receives email for the
     * domain, and the returned records then include the receiving MX record.
     * Receiving is off unless you ask for it. Sending is always enabled and can't be
     * disabled: `'sending' => 'disabled'` is rejected with a 422.
     *
     * @param array{
     *     'name': string, 'region'?: 'us-east-1'|'eu-west-1'|'sa-east-1'|'ap-northeast-1',
     *     'custom_return_path'?: string,
     *     'capabilities'?: array{'sending'?: 'enabled', 'receiving'?: 'enabled'|'disabled'}
     * } $parameters
     *
     * @see https://developer.send.io/docs/api#body-parameters
     */
    public function create(array $parameters): \Sendio\Domain
    {
        $payload = Payload::create('domains', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('domains', $result);
    }

    /**
     * List all domains.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Domain>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('domains', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('domains', $result);
    }

    /**
     * Update a domain with the given ID.
     *
     * Turn receiving on or off with `['capabilities' => ['receiving' => 'enabled'|'disabled']]`
     * (other parameters such as `open_tracking`, `click_tracking` and `tls` are unchanged).
     * - `'disabled'`: send.io stops accepting email for the domain and no longer requires
     *   its MX record, so remove the MX record pointing at send.io from your DNS.
     * - `'enabled'`: asks for the domain's MX record; publish it to start receiving
     *   email.
     * Sending can't be disabled.
     *
     * @param array<string, mixed> $parameters
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $id, array $parameters): \Sendio\Domain
    {
        $payload = Payload::update('domains', $id, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('domains', $result);
    }

    /**
     * Remove a domain with the given ID.
     *
     * @see https://developer.send.io/docs/api#path-parameters
     */
    public function remove(string $id): \Sendio\Domain
    {
        $payload = Payload::delete('domains', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('domains', $result);
    }

    /**
     * Verify a domain with the given ID.
     *
     * @see https://developer.send.io/docs/api#path-parameters
     */
    public function verify(string $id): \Sendio\Domain
    {
        $payload = Payload::verify('domains', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('domains', $result);
    }
}
