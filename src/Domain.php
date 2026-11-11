<?php

namespace Sendio;

/**
 * @property string $object The object type identifier.
 * @property string $id The unique identifier for the domain.
 * @property string $name The domain name.
 * @property string $status The verification status of the domain.
 * @property string $created_at Time at which the domain was created.
 * @property string $region The region the domain is located in.
 * @property bool $open_tracking Whether open tracking is enabled for the domain.
 * @property bool $click_tracking Whether click tracking is enabled for the domain.
 * @property string $tracking_subdomain The subdomain used for tracking links.
 * @property array $capabilities The capabilities of the domain (sending, receiving, etc).
 * @property string $dns_zone The DNS zone the domain lives in (acme.com for mail.acme.com).
 * @property array $records The list of DNS records to add to your domain. Each record has
 *     name (relative to the domain), host (relative to dns_zone: what to type into the DNS
 *     provider) and full_name (the complete record name).
 */
class Domain extends Resource
{
    //
}
