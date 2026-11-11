<?php

namespace Sendio\Service;

use Sendio\Contracts\Transporter;
use Sendio\Service\Automations\Run;
use Sendio\Service\Automations\Template;
use Sendio\ValueObjects\Transporter\Payload;

class Automation extends Service
{
    public Run $runs;

    public Template $templates;

    /**
     * Create a new automation service instance with the given transport.
     */
    public function __construct(Transporter $transporter)
    {
        $this->runs = new Run($transporter);
        $this->templates = new Template($transporter);

        parent::__construct($transporter);
    }

    /**
     * Retrieve a single automation.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $automationId): \Sendio\Automation
    {
        $payload = Payload::get('automations', $automationId);

        $result = $this->transporter->request($payload);

        return $this->createResource('automations', $result);
    }

    /**
     * Create a new automation to automate email sequences.
     *
     * @param array{
     *     name: string,
     *     status?: 'enabled'|'disabled',
     *     steps: array<int, array{
     *         key: string,
     *         type: 'trigger'|'send_email'|'delay'|'wait_for_event'|'condition'|'contact_update'|'contact_delete'|'add_to_segment',
     *         config: array
     *     }>,
     *     connections: array<int, array{from: string, to: string, type?: 'default'|'condition_met'|'condition_not_met'|'timeout'|'event_received'}>
     * } $parameters
     *
     * @see https://developer.send.io/docs/api
     */
    public function create(array $parameters): \Sendio\Automation
    {
        $payload = Payload::create('automations', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('automations', $result);
    }

    /**
     * Create a new automation from an automation template. The automation is created disabled.
     *
     * @param array{
     *     slug: string,
     *     version?: int,
     *     name?: string,
     *     events?: array<string, array{mode: 'create'|'existing', name: string}>,
     *     sender: array{from: string, reply_to?: string},
     *     topic_id?: string,
     *     params?: array<string, string>
     * } $parameters
     * @param array{'idempotency_key'?: string} $options
     *
     * @see https://developer.send.io/docs/api
     */
    public function createFromTemplate(array $parameters, array $options = []): \Sendio\Automation
    {
        $payload = Payload::create('automations/from-template', $parameters, $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('automations', $result);
    }

    /**
     * Retrieve a list of automations.
     *
     * @param array{'limit'?: int, 'before'?: string, 'after'?: string, 'status'?: 'enabled'|'disabled'} $options
     * @return \Sendio\Collection<\Sendio\Automation>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('automations', $options);

        $result = $this->transporter->request($payload);

        return $this->createResource('automations', $result);
    }

    /**
     * Update an existing automation.
     *
     * @param array{
     *     name?: string,
     *     status?: 'enabled'|'disabled',
     *     steps?: array<int, array{
     *         key: string,
     *         type: 'trigger'|'send_email'|'delay'|'wait_for_event'|'condition'|'contact_update'|'contact_delete'|'add_to_segment',
     *         config: array
     *     }>,
     *     connections?: array<int, array{from: string, to: string, type?: 'default'|'condition_met'|'condition_not_met'|'timeout'|'event_received'}>
     * } $parameters
     *
     * @see https://developer.send.io/docs/api
     */
    public function update(string $automationId, array $parameters): \Sendio\Automation
    {
        $payload = Payload::update('automations', $automationId, $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource('automations', $result);
    }

    /**
     * Remove an existing automation.
     *
     * @see https://developer.send.io/docs/api
     */
    public function remove(string $automationId): \Sendio\Automation
    {
        $payload = Payload::delete('automations', $automationId);

        $result = $this->transporter->request($payload);

        return $this->createResource('automations', $result);
    }

    /**
     * Duplicate an existing automation.
     *
     * @see https://developer.send.io/docs/api
     */
    public function duplicate(string $automationId): \Sendio\Automation
    {
        $payload = Payload::create("automations/$automationId/duplicate", []);

        $result = $this->transporter->request($payload);

        return $this->createResource('automations', $result);
    }

    /**
     * Stop a running automation.
     *
     * @see https://developer.send.io/docs/api
     */
    public function stop(string $automationId): \Sendio\Automation
    {
        $payload = Payload::create("automations/$automationId/stop", []);

        $result = $this->transporter->request($payload);

        return $this->createResource('automations', $result);
    }
}
