<?php

function transactionalEmail(array $overrides = []): array
{
    return array_merge([
        'object' => 'transactional_email',
        'id' => 'tx_2hX9Yq1bVv0kq3W4m8sZJt1aB7c',
        'name' => 'Sign-in code',
        'template_id' => '34a080c9-b17d-4187-ad80-5af20266e535',
        'template_name' => 'Sign-in code',
        'template_alias' => 'signin',
        'template_deleted' => false,
        'published_version_id' => '6a1f4c2e-8b3d-4e5f-9a7b-0c1d2e3f4a5b',
        'published_version_number' => 3,
        'from' => 'Acme <security@acme.com>',
        'subject' => 'Your code is {{CODE}}',
        'preview_text' => null,
        'reply_to' => [],
        'track_opens' => false,
        'track_clicks' => false,
        'created_via' => 'api',
        'created_at' => '2026-11-16T09:00:00Z',
        'updated_at' => '2026-11-16T09:05:00Z',
    ], $overrides);
}
