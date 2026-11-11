<?php

namespace Sendio\Automations;

use Sendio\Resource;

/**
 * @property string $object The type of object.
 * @property string $slug The unique slug of the automation template.
 * @property int $version The version of the automation template.
 * @property string $name The automation template name.
 * @property string $category The automation template category.
 * @property string $kind The kind of emails the template sends. Can be `marketing` or `transactional`.
 * @property string $outcome What the automation achieves.
 * @property null|string $how_it_works How the automation works (only present when retrieving a single template).
 * @property array $events The events the automation template reacts to.
 * @property int $email_count The number of emails in the automation template.
 * @property array $steps The automation template steps.
 * @property array $connections The automation template connections.
 * @property int $in_use_count The number of automations created from this template.
 * @property null|array $emails The emails sent by the automation template (only present when retrieving a single template).
 * @property null|array $params The configurable parameters of the automation template (only present when retrieving a single template).
 */
class Template extends Resource
{
    //
}
