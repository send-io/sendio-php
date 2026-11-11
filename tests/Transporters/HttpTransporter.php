<?php

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Client\ClientInterface;
use Sendio\Enums\Transporter\ContentType;
use Sendio\Exceptions\ErrorException;
use Sendio\Exceptions\TransporterException;
use Sendio\Exceptions\UnserializableResponse;
use Sendio\Transporters\HttpTransporter;
use Sendio\ValueObjects\ApiKey;
use Sendio\ValueObjects\Transporter\BaseUri;
use Sendio\ValueObjects\Transporter\Headers;
use Sendio\ValueObjects\Transporter\Payload;

beforeEach(function () {
    /** @var ClientInterface|Mockery\MockInterface */
    $this->client = Mockery::mock(ClientInterface::class);

    $apiKey = ApiKey::from('foo');

    $this->http = new HttpTransporter(
        $this->client,
        BaseUri::from('api.send.io'),
        Headers::withAuthorization($apiKey)->withContentType(ContentType::JSON)
    );
});

test('request', function () {
    $payload = Payload::create('email', ['to' => 'test@send.io']);
    $response = new Response(
        200,
        ['Content-Type' => 'application/json'],
        json_encode(['foo'])
    );

    $this->client
         ->shouldReceive('sendRequest')
         ->once()
         ->withArgs(function (Request $request) {
             expect($request->getMethod())->toBe('POST')
                ->and($request->getUri())
                ->getHost()->toBe('api.send.io')
                ->getScheme()->toBe('https')
                ->getPath()->toBe('/email');

             return true;
         })->andReturn($response);

    $this->http->request($payload);
});

test('request response', function () {
    $payload = Payload::create('email', ['to' => 'test@send.io']);
    $response = new Response(
        200,
        ['Content-Type' => 'application/json'],
        json_encode([
            'id' => 'test_123',
            'to' => 'test@send.io',
        ])
    );

    $this->client
         ->shouldReceive('sendRequest')
         ->once()
         ->andReturn($response);

    $response = $this->http->request($payload);

    expect($response);
});

test('request can handle string errors', function () {
    $payload = Payload::create('email', ['to' => 'test@send.io']);
    $response = new Response(
        401,
        ['Content-Type' => 'text/plain'],
        'Unauthorized'
    );

    $this->client
         ->shouldReceive('sendRequest')
         ->once()
         ->andReturn($response);

    $this->http->request($payload);
})->throws(UnserializableResponse::class, "Unexpected Content-Type 'text/plain'. Response body: Unauthorized");

test('request can handle client errors', function () {
    $payload = Payload::create('email', ['to' => 'test@send.io']);

    $baseUri = BaseUri::from('api.send.io');
    $headers = Headers::withAuthorization(ApiKey::from('foo'));

    $this->client
         ->shouldReceive('sendRequest')
         ->once()
         ->andThrow(new ConnectException('Could not resolve host.', $payload->toRequest($baseUri, $headers)));

    expect(fn () => $this->http->request($payload))->toThrow(function (TransporterException $exception) {
        expect($exception->getMessage())->toBe('Could not resolve host.')
            ->and($exception->getCode())->toBe(0)
            ->and($exception->getPrevious())->toBeInstanceOf(ConnectException::class);
    });
});

test('request can handle client exception json errors from the full response body', function () {
    $payload = Payload::create('email', ['to' => 'test@send.io']);
    $request = new Request('POST', 'https://api.send.io/email');
    $body = Utils::streamFor(json_encode(['error' => 'Unauthorized']));

    $body->getContents();

    $response = new Response(401, ['Content-Type' => 'application/json'], $body);

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andThrow(new ClientException('Unauthorized', $request, $response));

    $this->http->request($payload);
})->throws(ErrorException::class, 'Unauthorized');

test('request can handle serialization errors', function () {
    $payload = Payload::create('email', ['to' => 'test@send.io']);
    $response = new Response(200, ['content-type' => 'text/plain'], 'err');

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andReturn($response);

    $this->http->request($payload);
})->throws(UnserializableResponse::class, "Unexpected Content-Type 'text/plain'. Response body: err");

test('request can handle regular json errors', function () {
    $payload = Payload::create('email', ['to' => 'test@send.io']);
    $response = new Response(
        401,
        ['Content-Type' => 'application/json'],
        json_encode(['error' => 'Unauthorized'])
    );

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andReturn($response);

    $this->http->request($payload);
})->throws(ErrorException::class, 'Unauthorized');

test('request can throw sendio errors', function () {
    $payload = Payload::create('email', ['to' => 'test@send.io']);
    $response = new Response(422, ['content-type' => 'application/json'], json_encode([
        'statusCode' => 422,
        'name' => 'missing_required_field',
        'message' => 'Missing `to` field',
    ]));

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andReturn($response);

    expect(fn () => $this->http->request($payload))->toThrow(function (ErrorException $exception) {
        expect($exception->getMessage())->toBe('Missing `to` field')
            ->and($exception->getErrorMessage())->toBe('Missing `to` field')
            ->and($exception->getErrorCode())->toBe(422)
            ->and($exception->getErrorType())->toBe('missing_required_field');
    });
});

test('request throws every named send.io error', function (int $status, string $name) {
    $payload = Payload::create('mailboxes', ['address' => 'sales@acme.test']);
    $response = new Response($status, ['content-type' => 'application/json'], json_encode([
        'statusCode' => $status,
        'name' => $name,
        'message' => 'Something went wrong',
    ]));

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andReturn($response);

    expect(fn () => $this->http->request($payload))->toThrow(function (ErrorException $exception) use ($status, $name) {
        expect($exception->getErrorCode())->toBe($status)
            ->and($exception->getErrorType())->toBe($name);
    });
})->with([
    'conflict' => [409, 'conflict'],
    'service unavailable' => [503, 'service_unavailable'],
    'a name the SDK has never seen' => [418, 'brand_new_error'],
]);

test('request can throw json error', function () {
    $payload = Payload::create('email', ['to' => 'test@send.io']);
    $response = new Response(422, ['content-type' => 'application/json'], 'err');

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andReturn($response);

    $this->http->request($payload);
})->throws(UnserializableResponse::class, 'Syntax error');

test('request can handle server errors', function () {
    $payload = Payload::create('email', ['to' => 'test@send.io']);
    $response = new Response(422, ['content-type' => 'application/json'], json_encode([
        'error' => [
            'code' => 422,
            'type' => 'missing_required_field',
            'message' => 'Missing `to` field',
        ],
    ]));

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andReturn($response);

    expect(fn () => $this->http->request($payload))->toThrow(function (ErrorException $exception) {
        expect($exception->getMessage())->toBe('Missing `to` field')
            ->and($exception->getErrorMessage())->toBe('Missing `to` field')
            ->and($exception->getErrorCode())->toBe(422)
            ->and($exception->getErrorType())->toBe('missing_required_field');
    });
});

test('request can handle non json errors', function () {
    $payload = Payload::create('email', ['to' => 'test@send.io']);
    $response = new Response(200, ['content-type' => 'text/html'], 'err');

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andReturn($response);

    $this->http->request($payload);
})->throws(UnserializableResponse::class, "Unexpected Content-Type 'text/html'. Response body: err");

test('request throws on empty JSON response body', function () {
    $payload = Payload::create('email', ['to' => 'test@send.io']);
    $response = new Response(200, ['Content-Type' => 'application/json'], '');

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andReturn($response);

    $this->http->request($payload);
})->throws(UnserializableResponse::class, 'Empty response body');
