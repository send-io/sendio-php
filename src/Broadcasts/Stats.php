<?php

namespace Sendio\Broadcasts;

use Sendio\Resource;

/**
 * @property int $recipients Emails created for the broadcast.
 * @property int $sent Emails sent.
 * @property int $delivered Emails delivered.
 * @property int $bounced Emails bounced.
 * @property int $complained Emails marked as spam.
 * @property int $suppressed Emails suppressed.
 * @property int $failed Emails that failed.
 * @property int $opened Opens.
 * @property int $unique_opened Emails opened at least once.
 * @property int $clicked Clicks.
 * @property int $unique_clicked Emails clicked at least once.
 * @property int $unsubscribed Unsubscribes from this broadcast.
 * @property array{invalid: int} $skipped Contacts left out because email verification found them invalid.
 * @property float $delivery_rate Delivered over sent.
 * @property float $open_rate Unique opens over delivered.
 * @property float $click_rate Unique clicks over delivered.
 * @property float $bounce_rate Bounced over sent.
 * @property float $unsubscribe_rate Unsubscribed over delivered.
 */
class Stats extends Resource
{
    //
}
