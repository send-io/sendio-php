<?php

namespace Sendio\Transporters;

use Closure;
use GuzzleHttp\Exception\ClientException;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Sendio\Contracts\Transporter;
use Sendio\Exceptions\ErrorException;
use Sendio\Exceptions\TransporterException;
use Sendio\Exceptions\UnserializableResponse;
use Sendio\ValueObjects\Transporter\BaseUri;
use Sendio\ValueObjects\Transporter\Headers;
use Sendio\ValueObjects\Transporter\Payload;

class HttpTransporter implements Transporter
{
    /**
     * Create a new HTTP Transporter instance.
     */
    public function __construct(
        private readonly ClientInterface $client,
        private readonly BaseUri $baseUri,
        private readonly Headers $headers,
    ) {
        //
    }

    /**
     * Sends a request to the send.io API.
     *
     * @return array<array-key, mixed>
     *
     * @throws ErrorException|TransporterException|UnserializableResponse
     */
    public function request(Payload $payload): array
    {
        $request = $payload->toRequest($this->baseUri, $this->headers);

        $response = $this->sendRequest(fn () => $this->client->sendRequest($request));
        $contents = $response->getBody()->getContents();
        $contentType = $response->getHeaderLine('Content-Type');

        $this->throwIfJsonError($response, $contents);

        // Only decode as JSON if appropriate
        if (! str_contains($contentType, 'application/json')) {
            throw new UnserializableResponse(
                new JsonException(
                    "Unexpected Content-Type '{$contentType}'. Response body: " . substr($contents, 0, 200)
                ),
                $contents
            );
        }

        if (trim($contents) === '') {
            throw new UnserializableResponse(
                new JsonException('Empty response body'),
                $contents
            );
        }

        try {
            $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $jsonException) {
            throw new UnserializableResponse($jsonException);
        }

        return $data;
    }

    /**
     * Send the given request callable.
     */
    private function sendRequest(Closure $callable): ResponseInterface
    {
        try {
            return $callable();
        } catch (ClientExceptionInterface $clientException) {
            if ($clientException instanceof ClientException) {
                $response = $clientException->getResponse();

                $this->throwIfJsonError($response, (string) $response->getBody());
            }

            throw new TransporterException($clientException);
        }
    }

    /**
     * Throw an exception if there is a JSON error.
     */
    protected function throwIfJsonError(ResponseInterface $response, string $contents): void
    {
        if ($response->getStatusCode() < 400) {
            return;
        }

        // Only handle JSON content types...
        if (! str_contains($response->getHeaderLine('Content-Type'), 'application/json')) {
            return;
        }

        try {
            $response = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

            // Any named error counts, not a fixed list: the API answers with
            // names such as conflict (409) and service_unavailable (503) too.
            if (
                isset($response['error']) ||
                (isset($response['name']) && is_string($response['name']))
            ) {
                $error = $response['error'] ?? $response;

                if (! is_array($error)) {
                    $error = ['message' => is_string($error) ? $error : json_encode($error)];
                }

                throw new ErrorException($error);
            }
        } catch (JsonException $jsonException) {
            throw new UnserializableResponse($jsonException, $contents);
        }
    }
}
