<?php

use Sendio\Automations\Template;
use Sendio\Collection;

it('can get an automation template by slug', function () {
    $client = mockClient('GET', 'automation-templates/welcome-series', [], [], automationTemplate());

    $result = $client->automations->templates->get('welcome-series');

    expect($result)->toBeInstanceOf(Template::class)
        ->slug->toBe('welcome-series')
        ->emails->toBeArray()
        ->params->toBeArray();
});

it('can get a list of automation templates', function () {
    $client = mockClient('GET', 'automation-templates', [], [], automationTemplates());

    $result = $client->automations->templates->list();

    expect($result)->toBeInstanceOf(Collection::class)
        ->data->toBeArray()
        ->categories->toBeArray();

    expect($result->data[0])->toBeInstanceOf(Template::class)
        ->slug->toBe('welcome-series');
});

it('can get a list of automation templates filtered by category', function () {
    $client = mockClient('GET', 'automation-templates?category=onboarding', [], [], automationTemplates());

    $result = $client->automations->templates->list(['category' => 'onboarding']);

    expect($result)->toBeInstanceOf(Collection::class)
        ->data->toBeArray();
});
