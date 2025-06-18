<?php

declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;

trait ApiTestingTrait
{
    /**
     * The base API URL.
     */
    protected string $baseApiUrl = '/api';

    /**
     * The API version.
     */
    protected string $apiVersion = 'v1';

    /**
     * Get the full API URL.
     */
    protected function getApiUrl(string $path = ''): string
    {
        $path = mb_ltrim($path, '/');

        return "{$this->baseApiUrl}/{$this->apiVersion}/{$path}";
    }

    /**
     * Make a GET request to the API.
     */
    protected function getJson(string $path, array $parameters = [], array $headers = []): TestResponse
    {
        return $this->json('GET', $this->getApiUrl($path), $parameters, $headers);
    }

    /**
     * Make a POST request to the API.
     */
    protected function postJson(string $path, array $data = [], array $headers = []): TestResponse
    {
        return $this->json('POST', $this->getApiUrl($path), $data, $headers);
    }

    /**
     * Make a PUT request to the API.
     */
    protected function putJson(string $path, array $data = [], array $headers = []): TestResponse
    {
        return $this->json('PUT', $this->getApiUrl($path), $data, $headers);
    }

    /**
     * Make a PATCH request to the API.
     */
    protected function patchJson(string $path, array $data = [], array $headers = []): TestResponse
    {
        return $this->json('PATCH', $this->getApiUrl($path), $data, $headers);
    }

    /**
     * Make a DELETE request to the API.
     */
    protected function deleteJson(string $path, array $data = [], array $headers = []): TestResponse
    {
        return $this->json('DELETE', $this->getApiUrl($path), $data, $headers);
    }

    /**
     * Assert that the response has a successful status code.
     */
    protected function assertSuccessful(TestResponse $response): void
    {
        $response->assertSuccessful();
    }

    /**
     * Assert that the response has a 201 status code.
     */
    protected function assertCreated(TestResponse $response): void
    {
        $response->assertCreated();
    }

    /**
     * Assert that the response has a 204 status code.
     */
    protected function assertNoContent(TestResponse $response): void
    {
        $response->assertNoContent();
    }

    /**
     * Assert that the response has a 400 status code.
     */
    protected function assertBadRequest(TestResponse $response): void
    {
        $response->assertStatus(400);
    }

    /**
     * Assert that the response has a 401 status code.
     */
    protected function assertUnauthorized(TestResponse $response): void
    {
        $response->assertUnauthorized();
    }

    /**
     * Assert that the response has a 403 status code.
     */
    protected function assertForbidden(TestResponse $response): void
    {
        $response->assertForbidden();
    }

    /**
     * Assert that the response has a 404 status code.
     */
    protected function assertNotFound(TestResponse $response): void
    {
        $response->assertNotFound();
    }

    /**
     * Assert that the response has a 422 status code.
     */
    protected function assertValidationError(TestResponse $response): void
    {
        $response->assertStatus(422);
    }

    /**
     * Assert that the response has validation errors for the given fields.
     */
    protected function assertHasValidationErrors(TestResponse $response, array|string $fields): void
    {
        $response->assertInvalid($fields);
    }

    /**
     * Assert that the response is a JSON response with the given structure.
     */
    protected function assertJsonStructure(TestResponse $response, array $structure, string $path = ''): void
    {
        $response->assertJsonStructure($structure, $path);
    }

    /**
     * Assert that the response contains pagination data.
     */
    protected function assertPaginated(TestResponse $response): void
    {
        $response->assertJsonStructure([
            'data',
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'],
        ]);
    }

    /**
     * Assert that the response contains the given data.
     */
    protected function assertJsonData(TestResponse $response, array $data): void
    {
        $response->assertJson(['data' => $data]);
    }

    /**
     * Assert that the response contains an error message.
     */
    protected function assertErrorMessage(TestResponse $response, string $message): void
    {
        $response->assertJson(['message' => $message]);
    }

    /**
     * Assert that the response contains a success message.
     */
    protected function assertSuccessMessage(TestResponse $response, string $message): void
    {
        $response->assertJson(['message' => $message]);
    }
}
