<?php

namespace Sendio\Contracts;

use Sendio\Exceptions\ErrorException;
use Sendio\Exceptions\TransporterException;
use Sendio\Exceptions\UnserializableResponse;
use Sendio\ValueObjects\Transporter\Payload;

interface Transporter
{
    /**
     * Sends a request to the send.io API.
     *
     * @return array<array-key, mixed>
     *
     * @throws ErrorException|TransporterException|UnserializableResponse
     */
    public function request(Payload $payload): array;
}
