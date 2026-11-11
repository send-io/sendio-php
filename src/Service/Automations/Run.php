<?php

namespace Sendio\Service\Automations;

use Sendio\Service\Service;
use Sendio\ValueObjects\Transporter\Payload;

class Run extends Service
{
    /**
     * Retrieve a single automation run.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $automationId, string $runId): \Sendio\Automations\Run
    {
        $payload = Payload::get("automations/$automationId/runs", $runId);

        $result = $this->transporter->request($payload);

        return $this->createResource('automation-runs', $result);
    }

    /**
     * Retrieve a list of automation runs.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string, 'status'?: string} $options
     * @return \Sendio\Collection<\Sendio\Automations\Run>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(string $automationId, array $options = []): \Sendio\Collection
    {
        $payload = Payload::list("automations/$automationId/runs", $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('automation-runs', $result);
    }
}
