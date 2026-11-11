<?php

namespace Sendio\Service\Suppressions;

use InvalidArgumentException;
use Sendio\Service\Service;
use Sendio\ValueObjects\Transporter\Payload;

class Batch extends Service
{
    /**
     * Add up to 100 email addresses to the suppression list at once.
     *
     * @param array{'emails': string[]} $parameters
     * @return \Sendio\Collection<\Sendio\Suppression>
     *
     * @see https://developer.send.io/docs/api
     */
    public function add(array $parameters): \Sendio\Collection
    {
        $payload = Payload::create('suppressions/batch/add', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('suppressions', $result);
    }

    /**
     * Remove up to 100 suppressions from the suppression list at once.
     *
     * @param array{'emails'?: string[], 'ids'?: string[]} $parameters
     * @return \Sendio\Collection<\Sendio\Suppression>
     *
     * @see https://developer.send.io/docs/api
     */
    public function remove(array $parameters): \Sendio\Collection
    {
        $hasEmails = array_key_exists('emails', $parameters);
        $hasIds = array_key_exists('ids', $parameters);

        if (! ($hasEmails xor $hasIds)) {
            throw new InvalidArgumentException("Provide either 'emails' or 'ids', but not both.");
        }

        $payload = Payload::create('suppressions/batch/remove', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('suppressions', $result);
    }
}
