<?php

function automationTemplate(): array
{
    return [
        'object' => 'automation_template',
        'slug' => 'welcome-series',
        'version' => 1,
        'name' => 'Welcome series',
        'category' => 'onboarding',
        'kind' => 'marketing',
        'outcome' => 'Turn new sign-ups into active users.',
        'how_it_works' => 'Sends a welcome email when a user signs up, then a follow-up two days later.',
        'events' => [
            [
                'ref' => 'signup',
                'name' => 'user.created',
                'purpose' => 'Starts the automation when a user signs up.',
                'exists' => false,
                'schema' => ['first_name' => 'string'],
                'adds' => [],
            ],
        ],
        'email_count' => 1,
        'steps' => [
            ['key' => 'trigger', 'type' => 'trigger'],
            ['key' => 'send_welcome', 'type' => 'send_email'],
        ],
        'connections' => [
            ['from' => 'trigger', 'to' => 'send_welcome', 'type' => 'default'],
        ],
        'in_use_count' => 0,
        'emails' => [
            [
                'ref' => 'welcome',
                'step_label' => 'Welcome email',
                'step_key' => 'send_welcome',
                'subject' => 'Welcome!',
                'preview' => 'Glad to have you.',
                'html' => '<p>Welcome!</p>',
                'text' => 'Welcome!',
                'variables' => ['first_name'],
            ],
        ],
        'params' => [
            [
                'ref' => 'follow_up_delay',
                'type' => 'duration',
                'label' => 'Follow-up delay',
                'default' => '2d',
            ],
        ],
    ];
}

function automationTemplates(): array
{
    return [
        'object' => 'list',
        'categories' => ['onboarding', 'billing', 'ecommerce', 'engagement', 'lead_capture', 'internal'],
        'data' => [
            [
                'object' => 'automation_template',
                'slug' => 'welcome-series',
                'version' => 1,
                'name' => 'Welcome series',
                'category' => 'onboarding',
                'kind' => 'marketing',
                'outcome' => 'Turn new sign-ups into active users.',
                'events' => [
                    ['ref' => 'signup', 'name' => 'user.created', 'purpose' => 'Starts the automation when a user signs up.', 'exists' => false],
                ],
                'email_count' => 1,
                'steps' => [
                    ['key' => 'trigger', 'type' => 'trigger'],
                    ['key' => 'send_welcome', 'type' => 'send_email'],
                ],
                'connections' => [
                    ['from' => 'trigger', 'to' => 'send_welcome', 'type' => 'default'],
                ],
                'in_use_count' => 0,
            ],
        ],
    ];
}

function automationFromTemplate(): array
{
    return [
        'object' => 'automation',
        'id' => 'c9b16d4f-ba6c-4e2e-b044-6bf4404e57fd',
        'status' => 'disabled',
        'source_template' => ['slug' => 'welcome-series', 'version' => 1, 'name' => 'Welcome series'],
        'emails' => [],
        'events_created' => ['user.created'],
        'events_reused' => [],
    ];
}
