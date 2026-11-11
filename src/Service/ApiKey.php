<?php

namespace Sendio\Service;

use Sendio\ValueObjects\Transporter\Payload;

class ApiKey extends Service
{
    /**
     * Create a new API key.
     *
     * @see https://developer.send.io/docs/api#body-parameters
     */
    public function create(array $parameters): \Sendio\ApiKey
    {
        $payload = Payload::create('api-keys', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('api-keys', $result);
    }

    /**
     * List all API keys.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\ApiKey>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('api-keys', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('api-keys', $result);
    }

    /**
     * Update an API key with the given ID.
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $id, array $parameters): \Sendio\ApiKey
    {
        $payload = Payload::update('api-keys', $id, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('api-keys', $result);
    }

    /**
     * Remove an API key with the given ID.
     *
     * @see https://developer.send.io/docs/api#path-parameters
     */
    public function remove(string $id): \Sendio\ApiKey
    {
        $payload = Payload::delete('api-keys', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('api-keys', $result);
    }
}
