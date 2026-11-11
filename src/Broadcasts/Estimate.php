<?php

namespace Sendio\Broadcasts;

use Sendio\Resource;

/**
 * @property string $object The type of object, `broadcast_estimate`.
 * @property int $recipients How many contacts the audience reaches now.
 * @property array{invalid: int} $skipped Contacts left out because email verification found them invalid.
 */
class Estimate extends Resource
{
    //
}
