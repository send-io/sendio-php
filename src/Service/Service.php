<?php

namespace Sendio\Service;

use Sendio\ApiKey;
use Sendio\Audience;
use Sendio\Automation;
use Sendio\Automations\Run as AutomationRun;
use Sendio\Automations\Template as AutomationTemplate;
use Sendio\Broadcast;
use Sendio\Broadcasts\ClickedLink as BroadcastClickedLink;
use Sendio\Broadcasts\Estimate as BroadcastEstimate;
use Sendio\Broadcasts\Recipient as BroadcastRecipient;
use Sendio\Broadcasts\Stats as BroadcastStats;
use Sendio\Broadcasts\Version as BroadcastVersion;
use Sendio\Collection;
use Sendio\Contact;
use Sendio\ContactProperty;
use Sendio\Contacts\Import as ContactImport;
use Sendio\Contacts\Topic as ContactTopic;
use Sendio\Contracts\Transporter;
use Sendio\Domain;
use Sendio\Email;
use Sendio\Emails\Attachment;
use Sendio\Emails\Receiving;
use Sendio\Event;
use Sendio\Log;
use Sendio\Mailbox;
use Sendio\Mailboxes\Credentials as MailboxCredentials;
use Sendio\Metrics;
use Sendio\Resource;
use Sendio\Segment;
use Sendio\Suppression;
use Sendio\Template;
use Sendio\Topic;
use Sendio\TransactionalEmail;
use Sendio\Usage;
use Sendio\Webhook;
use Sendio\Webhooks\Event as WebhookEvent;
use Sendio\Webhooks\EventAttempt as WebhookEventAttempt;

abstract class Service
{
    /**
     * @var array<string, \Sendio\Resource>
     */
    protected $mapping = [
        'api-keys' => ApiKey::class,
        'attachments' => Attachment::class,
        'audiences' => Audience::class,
        'automation-runs' => AutomationRun::class,
        'automation-templates' => AutomationTemplate::class,
        'automations' => Automation::class,
        'broadcasts' => Broadcast::class,
        'broadcast-clicked-links' => BroadcastClickedLink::class,
        'broadcast-estimates' => BroadcastEstimate::class,
        'broadcast-recipients' => BroadcastRecipient::class,
        'broadcast-stats' => BroadcastStats::class,
        'broadcast-versions' => BroadcastVersion::class,
        'contact-imports' => ContactImport::class,
        'contact-properties' => ContactProperty::class,
        'contact-topics' => ContactTopic::class,
        'contacts' => Contact::class,
        'domains' => Domain::class,
        'emails' => Email::class,
        'emails-metrics' => Metrics::class,
        'events' => Event::class,
        'logs' => Log::class,
        'mailboxes' => Mailbox::class,
        'mailbox-credentials' => MailboxCredentials::class,
        'receiving' => Receiving::class,
        'segments' => Segment::class,
        'suppressions' => Suppression::class,
        'templates' => Template::class,
        'transactional' => TransactionalEmail::class,
        'topics' => Topic::class,
        'usage' => Usage::class,
        'webhook-event-attempts' => WebhookEventAttempt::class,
        'webhook-events' => WebhookEvent::class,
        'webhooks' => Webhook::class,
    ];

    /**
     * Create a service instance with the given transporter.
     */
    public function __construct(
        protected readonly Transporter $transporter
    ) {
        //
    }

    /**
     * Create a new resource for the given  with the given attributes.
     *
     * By default, whether this returns a single resource or a Collection is
     * auto-detected from the presence of a `data` array in $attributes. Pass
     * $asList explicitly when a resource's own attributes happen to include
     * a `data` key that isn't a list of $resourceType items (e.g. metrics'
     * `data` is a breakdown array, not a list of Metrics resources).
     */
    protected function createResource(string $resourceType, array $attributes, ?bool $asList = null)
    {
        $class = isset($this->mapping[$resourceType]) ? $this->mapping[$resourceType] : Resource::class;

        $isList = $asList ?? (isset($attributes['data']) && is_array($attributes['data']));

        if ($isList) {
            foreach ($attributes['data'] as $key => $value) {
                $attributes['data'][$key] = $class::from($value);
            }

            return Collection::from($attributes);
        } else {
            return $class::from($attributes);
        }
    }
}
