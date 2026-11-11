<?php

namespace Sendio\Service\Automations;

use Sendio\Service\Service;
use Sendio\ValueObjects\Transporter\Payload;

class Template extends Service
{
    /**
     * Retrieve a single automation template, including its emails and params.
     *
     * @see https://developer.send.io/docs/api
     */
    public function get(string $slug): \Sendio\Automations\Template
    {
        $payload = Payload::get('automation-templates', $slug);

        $result = $this->transporter->request($payload);

        return $this->createResource('automation-templates', $result);
    }

    /**
     * Retrieve the catalog of automation templates. This list is not paginated.
     *
     * @param array{'category'?: 'onboarding'|'billing'|'ecommerce'|'engagement'|'lead_capture'|'internal'} $options
     * @return \Sendio\Collection<\Sendio\Automations\Template>
     *
     * @see https://developer.send.io/docs/api
     */
    public function list(array $options = []): \Sendio\Collection
    {
        $payload = Payload::list('automation-templates', array_intersect_key($options, ['category' => true]), ['category']);

        $result = $this->transporter->request($payload);

        return $this->createResource('automation-templates', $result);
    }
}
