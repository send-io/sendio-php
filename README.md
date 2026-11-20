# send.io PHP SDK

The official PHP library for the [send.io](https://send.io) email API.

> Requires PHP 8.1+

Full API reference: https://developer.send.io/docs/api

## Install

```bash
composer require sendio/sendio-php
```

## Quick start

Create an API key in the send.io dashboard (keys start with `mx_`) and send an email:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$sendio = Sendio::client(getenv('SENDIO_API_KEY'));

$email = $sendio->emails->send([
    'from' => 'Acme <onboarding@send.io>',
    'to' => ['delivered@send.io'],
    'subject' => 'Hello from send.io',
    'html' => '<p>It works!</p>',
]);

echo $email->id;
```

This example sends from `onboarding@send.io` to the test address `delivered@send.io`, so it works on a new account before you add a domain. `onboarding@send.io` can only send to test addresses. To email real people, send from an address on a domain you verified. A free mailbox works too once Mailboxes shows it can send.

The `from` address must use a domain you have verified in send.io.

### Idempotency

Pass an idempotency key so a retried request does not send twice:

```php
$sendio->emails->send($params, ['idempotency_key' => 'welcome-user/123']);
```

## Batch sending

Send up to 100 emails in one request:

```php
$result = $sendio->batch->send([
    [
        'from' => 'Acme <onboarding@yourdomain.com>',
        'to' => ['delivered@send.io'],
        'subject' => 'First',
        'text' => 'First email',
    ],
    [
        'from' => 'Acme <onboarding@yourdomain.com>',
        'to' => ['delivered@send.io'],
        'subject' => 'Second',
        'text' => 'Second email',
    ],
]);

foreach ($result->data as $item) {
    echo $item['id'], PHP_EOL;
}
```

## Retrieve an email

```php
$email = $sendio->emails->get('4ef9a417-02e9-4d39-ad75-9611e0fcc33c');

echo $email->last_event; // e.g. "delivered"
```

You can also list, update (reschedule) and cancel scheduled emails with `emails->list()`, `emails->update()` and `emails->cancel()`.

## Domains

```php
$domain = $sendio->domains->create(['name' => 'yourdomain.com']);

// Add the returned DNS records at your DNS provider, then:
$sendio->domains->verify($domain->id);

$domains = $sendio->domains->list();
foreach ($domains->data as $d) {
    echo $d['name'], ' ', $d['status'], PHP_EOL;
}

$sendio->domains->get($domain->id);
$sendio->domains->remove($domain->id);
```

### Receiving email on a domain

Receiving is off unless you ask for it. Add a domain with receiving to get the receiving MX
record in its DNS records:

```php
$domain = $sendio->domains->create([
    'name' => 'yourdomain.com',
    'capabilities' => ['receiving' => 'enabled'],
]);

// Stop receiving: send.io stops accepting email for the domain. Remove the MX record
// pointing at send.io from your DNS afterwards.
$sendio->domains->update($domain->id, ['capabilities' => ['receiving' => 'disabled']]);

// Turn it back on (asks for the domain's MX record; publish it to start receiving email).
$sendio->domains->update($domain->id, ['capabilities' => ['receiving' => 'enabled']]);
```

Sending is always enabled and can't be disabled.

## Transactional emails

A transactional email (a sign-in code, a password reset, a receipt) is a saved
send of a published template: it keeps the From, subject, reply-to and
tracking (off by default), and its content is always the template's published
version. Create it once, then send it by id with only the recipient and the
variables.

```php
$tx = $sendio->transactional->create([
    'name' => 'Sign-in code',
    'template_id' => 'signin-code', // a published template, by id or alias
    'from' => 'Acme <security@yourdomain.com>',
    'subject' => 'Your code is {{CODE}}',
]);

$sendio->emails->send([
    'transactional_id' => $tx->id,
    'to' => 'ada@example.com',
    'variables' => ['CODE' => '123456'],
]);
```

`$sendio->transactional` also has `list` (`search`, `template_id`), `get`,
`update` (pass `expected_updated_at` to get a 409 instead of overwriting
someone else's change), `remove`, `test` and `stats`.

## Mailboxes

Mailboxes are real email accounts with an IMAP/SMTP login. The domain must be verified.

```php
$mailbox = $sendio->mailboxes->create(['address' => 'ada@yourdomain.com']);
// or: ['local_part' => 'ada', 'domain_id' => $domain->id]

// Shown once, when IMAP/SMTP is ready. The password is also an API key for this mailbox.
echo $mailbox->credentials['password'] ?? '';

$mailboxes = $sendio->mailboxes->list();
$sendio->mailboxes->update($mailbox->id, ['warmup_enabled' => false]);

// The old password stops working.
$reset = $sendio->mailboxes->resetPassword($mailbox->id);
echo $reset->credentials['smtp']['host'];

// Deletes the email account and any agent inbox on it.
$sendio->mailboxes->remove($mailbox->id);
```

## Other resources

The client exposes every send.io resource as a property:
`apiKeys`, `audiences`, `automations`, `batch`, `broadcasts`, `contacts`, `contactProperties`,
`domains`, `emails` (including `emails->receiving` and `emails->attachments`), `events`, `logs`,
`mailboxes`, `segments`, `suppressions`, `templates`, `topics`, `usage` and `webhooks`.

```php
$keys = $sendio->apiKeys->list();
$contact = $sendio->contacts->create(['email' => 'ada@example.com']);
```

## Webhook signatures

```php
$valid = $sendio->webhooks->verify($payload, $headers, $signingSecret); // true, or throws
```

`$headers` must contain `svix-id`, `svix-timestamp` and `svix-signature` from the incoming request.
A `Sendio\Exceptions\WebhookSignatureVerificationException` is thrown when verification fails.

## Configuration

| Setting | How |
|---|---|
| API key | First argument of `Sendio::client()`. Read it from `SENDIO_API_KEY` in your environment. |
| Base URL | Second argument of `Sendio::client()`, or the `SENDIO_BASE_URL` environment variable. Default `https://api.send.io`. |

```php
// Explicit base URL, for example a test environment:
$sendio = Sendio::client(getenv('SENDIO_API_KEY'), 'https://api.send.io');

// Or from the environment:
// SENDIO_BASE_URL=https://api.send.io
$sendio = Sendio::client(getenv('SENDIO_API_KEY'));
```

The argument wins over the environment variable. Every request sends the header
`User-Agent: sendio-php:<version>`.

## Error handling

API errors throw `Sendio\Exceptions\ErrorException`; network and HTTP transport failures throw
`Sendio\Exceptions\TransporterException`.

```php
use Sendio\Exceptions\ErrorException;
use Sendio\Exceptions\TransporterException;

try {
    $sendio->emails->send($params);
} catch (ErrorException $e) {
    echo $e->getErrorCode();    // HTTP status, e.g. 422
    echo $e->getErrorType();    // e.g. "validation_error"
    echo $e->getErrorMessage(); // human readable message from send.io
} catch (TransporterException $e) {
    echo 'Could not reach send.io: ' . $e->getMessage();
}
```

## Laravel

Use [`sendio/sendio-laravel`](https://packagist.org/packages/sendio/sendio-laravel) for a Laravel mail
transport, facade and webhook handling built on this SDK.

## Development

```bash
composer install
vendor/bin/pest

# Live smoke test (sends one email):
SENDIO_API_KEY=mx_... SENDIO_BASE_URL=https://api.send.io php examples/smoke.php
```

## Releasing

For maintainers. Releases go out from GitHub Actions on the public copy of this SDK, [send-io/sendio-php](https://github.com/send-io/sendio-php), not from a laptop:

1. In send-io-core, bump the version in `src/Sendio.php` (`VERSION`), add the `CHANGELOG.md` entry and update the version shown in the console docs (a console test compares them), then merge into `develop`.
2. Sync the public copy: replace its files with this folder's tracked files at the merged commit (`git ls-files`, since `.gitattributes` drops the tests from archives), commit it as `sync with send-io-core <short sha>` and push `main`.
3. Run the Tag release workflow (`.github/workflows/tag.yml`) by hand on the public repository. It reads the version from `src/Sendio.php` (`VERSION`), runs the tests and pushes the matching `vX.Y.Z` tag, or does nothing if the tag exists.

Packagist has no hook on the repository yet: press Update on packagist.org/packages/sendio/sendio-php.

## License

MIT. See [LICENSE](LICENSE) and [NOTICE](NOTICE).
