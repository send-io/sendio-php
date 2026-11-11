<?php

namespace Sendio\Service;

use Sendio\Contracts\Transporter;
use Sendio\Service\Suppressions\Batch;
use Sendio\ValueObjects\Transporter\Payload;

class Suppression extends Service
{
    public Batch $batch;

    /**
     * Create a new suppression service instance with the given transport.
     */
    public function __construct(Transporter $transporter)
    {
        $this->batch = new Batch($transporter);

        parent::__construct($transporter);
    }

    /**
     * Retrieve a single suppression by ID or email.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $idOrEmail): \Sendio\Suppression
    {
        $payload = Payload::get('suppressions', $idOrEmail);

        $result = $this->transporter->request($payload);

        return $this->createResource('suppressions', $result);
    }

    /**
     * Add an email address to the suppression list.
     *
     * @param array{'email': string} $parameters
     * @see https://developer.send.io/docs/api
     */
    public function add(array $parameters): \Sendio\Suppression
    {
        $payload = Payload::create('suppressions', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('suppressions', $result);
    }

    /**
     * List all suppressions.
     *
     * @param array{'origin'?: string, 'limit'?: int, 'before'?: string, 'after'?: string} $options
     * @return \Sendio\Collection<\Sendio\Suppression>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('suppressions', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('suppressions', $result);
    }

    /**
     * Remove a single suppression by ID or email.
     *
     * @see https://developer.send.io/docs/api
     */
    public function remove(string $idOrEmail): \Sendio\Suppression
    {
        $payload = Payload::delete('suppressions', $idOrEmail);

        $result = $this->transporter->request($payload);

        return $this->createResource('suppressions', $result);
    }
}
