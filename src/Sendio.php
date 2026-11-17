<?php

use GuzzleHttp\Client as GuzzleClient;
use Sendio\Client;
use Sendio\Transporters\HttpTransporter;
use Sendio\ValueObjects\ApiKey;
use Sendio\ValueObjects\Transporter\BaseUri;
use Sendio\ValueObjects\Transporter\Headers;

class Sendio
{
    /**
     * The current SDK version.
     */
    public const VERSION = '1.1.0';

    /**
     * The default send.io API base URL.
     */
    public const DEFAULT_BASE_URL = 'https://api.send.io';

    /**
     * Creates a new send.io Client with the given API key.
     *
     * The base URL is resolved in this order: the $baseUrl argument, the
     * SENDIO_BASE_URL environment variable, then https://api.send.io.
     */
    public static function client(string $apiKey, ?string $baseUrl = null): Client
    {
        $apiKey = ApiKey::from($apiKey);
        $baseUri = BaseUri::from(self::resolveBaseUrl($baseUrl));
        $headers = Headers::withAuthorization($apiKey);

        $client = new GuzzleClient();
        $transporter = new HttpTransporter($client, $baseUri, $headers);

        return new Client($transporter);
    }

    /**
     * Resolve the base URL from the given value, the environment, or the default.
     */
    public static function resolveBaseUrl(?string $baseUrl = null): string
    {
        if (is_string($baseUrl) && $baseUrl !== '') {
            return $baseUrl;
        }

        $env = getenv('SENDIO_BASE_URL');

        return is_string($env) && $env !== '' ? $env : self::DEFAULT_BASE_URL;
    }
}
