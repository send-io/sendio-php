<?php

function mailboxCredentials(): array
{
    return [
        'username' => 'ada@example.com',
        'password' => 'mx_mailbox_secret',
        'imap' => [
            'host' => 'imap.send.io',
            'port' => 993,
            'security' => 'ssl',
        ],
        'smtp' => [
            'host' => 'smtp.send.io',
            'port' => 587,
            'security' => 'starttls',
        ],
    ];
}

function mailbox(array $overrides = []): array
{
    return array_merge([
        'object' => 'mailbox',
        'id' => '2f1c4a7e-7d0b-4b3e-9a51-6f8d1e2c3b4a',
        'address' => 'ada@example.com',
        'kind' => 'normal',
        'agent_inbox_id' => null,
        'imap_smtp' => 'ready',
        'domain_id' => '4dd369bc-aa82-4ff3-97de-514ae3000ee0',
        'domain' => 'example.com',
        'warmup_enabled' => true,
        'warmup_changed_at' => null,
        'created_at' => '2026-10-01T10:00:00Z',
        'sent' => 12,
        'sent_total' => 40,
        'delivered' => 11,
        'bounced' => 1,
        'complained' => 0,
        'last_sent_at' => '2026-10-20T09:30:00Z',
        'health' => 'healthy',
    ], $overrides);
}

function mailboxes(): array
{
    return [
        'object' => 'list',
        'has_more' => false,
        'data' => [
            mailbox(),
            mailbox([
                'id' => '9b8e7d6c-5a4b-4c3d-8e2f-1a0b9c8d7e6f',
                'address' => 'grace@example.com',
                'kind' => 'agent',
                'agent_inbox_id' => 'c1d2e3f4-a5b6-4c7d-8e9f-0a1b2c3d4e5f',
                'imap_smtp' => 'pending',
                'warmup_enabled' => false,
                'health' => 'no_data',
            ]),
        ],
    ];
}
