<?php

namespace Sendio\Service;

use Sendio\ValueObjects\Transporter\Payload;

class Usage extends Service
{
    /**
     * Retrieve the caller's account-level usage and quota data.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(): \Sendio\Usage
    {
        $payload = Payload::list('usage');

        $result = $this->transporter->request($payload);

        return $this->createResource('usage', $result);
    }
}
