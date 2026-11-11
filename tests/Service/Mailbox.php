<?php

use Sendio\Collection;
use Sendio\Mailbox;
use Sendio\Mailboxes\Credentials;

it('can get a list of mailbox resources', function () {
    $client = mockClient('GET', 'mailboxes', [], [], mailboxes());

    $result = $client->mailboxes->list();

    expect($result)->toBeInstanceOf(Collection::class)
        ->data->toHaveCount(2)
        ->and($result->data[0])->toBeInstanceOf(Mailbox::class)
        ->and($result->data[1]->kind)->toBe('agent')
        ->and($result->data[1]->warmup_enabled)->toBeFalse();
});

it('can create a mailbox resource', function () {
    $client = mockClient('POST', 'mailboxes', [
        'local_part' => 'ada',
        'domain_id' => '4dd369bc-aa82-4ff3-97de-514ae3000ee0',
    ], [], mailbox(['credentials' => mailboxCredentials()]));

    $result = $client->mailboxes->create([
        'local_part' => 'ada',
        'domain_id' => '4dd369bc-aa82-4ff3-97de-514ae3000ee0',
    ]);

    expect($result)->toBeInstanceOf(Mailbox::class)
        ->id->toBe('2f1c4a7e-7d0b-4b3e-9a51-6f8d1e2c3b4a')
        ->address->toBe('ada@example.com')
        ->imap_smtp->toBe('ready')
        ->and($result->credentials['password'])->toBe('mx_mailbox_secret')
        ->and($result->credentials['imap']['port'])->toBe(993)
        ->and($result->credentials['smtp']['security'])->toBe('starttls');
});

it('can create a free mailbox resource', function () {
    $client = mockClient('POST', 'mailboxes', [
        'username' => 'ada',
        'display_name' => 'Ada Lovelace',
    ], [], mailbox(['domain_id' => null, 'domain' => null, 'pool' => 'free']));

    $result = $client->mailboxes->create([
        'username' => 'ada',
        'display_name' => 'Ada Lovelace',
    ]);

    expect($result)->toBeInstanceOf(Mailbox::class)
        ->domain_id->toBeNull()
        ->pool->toBe('free');
});

it('can update a mailbox resource', function () {
    $client = mockClient('PATCH', 'mailboxes/2f1c4a7e-7d0b-4b3e-9a51-6f8d1e2c3b4a', [
        'warmup_enabled' => false,
    ], [], mailbox(['warmup_enabled' => false]));

    $result = $client->mailboxes->update('2f1c4a7e-7d0b-4b3e-9a51-6f8d1e2c3b4a', [
        'warmup_enabled' => false,
    ]);

    expect($result)->toBeInstanceOf(Mailbox::class)
        ->warmup_enabled->toBeFalse();
});

it('can reset the password of a mailbox', function () {
    $client = mockClient('POST', 'mailboxes/2f1c4a7e-7d0b-4b3e-9a51-6f8d1e2c3b4a/password', [], [], [
        'object' => 'mailbox_credentials',
        'id' => '2f1c4a7e-7d0b-4b3e-9a51-6f8d1e2c3b4a',
        'address' => 'ada@example.com',
        'credentials' => mailboxCredentials(),
        'note' => 'The old password no longer works.',
    ]);

    $result = $client->mailboxes->resetPassword('2f1c4a7e-7d0b-4b3e-9a51-6f8d1e2c3b4a');

    expect($result)->toBeInstanceOf(Credentials::class)
        ->object->toBe('mailbox_credentials')
        ->address->toBe('ada@example.com')
        ->note->toBe('The old password no longer works.')
        ->and($result->credentials['username'])->toBe('ada@example.com')
        ->and($result->credentials['password'])->toBe('mx_mailbox_secret')
        ->and($result->credentials['imap']['host'])->toBe('imap.send.io');
});

it('can remove a mailbox resource', function () {
    $client = mockClient('DELETE', 'mailboxes/2f1c4a7e-7d0b-4b3e-9a51-6f8d1e2c3b4a', [], [], [
        'object' => 'mailbox',
        'id' => '2f1c4a7e-7d0b-4b3e-9a51-6f8d1e2c3b4a',
        'deleted' => true,
    ]);

    $result = $client->mailboxes->remove('2f1c4a7e-7d0b-4b3e-9a51-6f8d1e2c3b4a');

    expect($result)->toBeInstanceOf(Mailbox::class)
        ->id->toBe('2f1c4a7e-7d0b-4b3e-9a51-6f8d1e2c3b4a')
        ->deleted->toBeTrue();
});
