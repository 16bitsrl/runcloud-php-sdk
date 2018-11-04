<?php

namespace App\RuncloudApi;

use Psr\Http\Message\ResponseInterface;
use App\RuncloudApi\Exceptions\UnwantedRedirectException;
use App\RuncloudApi\Exceptions\AuthenticationFailedException;
use App\RuncloudApi\Exceptions\ForbiddenRequestException;
use App\RuncloudApi\Exceptions\NotFoundException;
use App\RuncloudApi\Exceptions\ValidationException;
use App\RuncloudApi\Exceptions\TooManyRequestsException;
use App\RuncloudApi\Exceptions\TimeoutException;


trait MakesHttpRequests
{
    /**
     * Make a GET request to Runcloud servers and return the response.
     *
     * @param  string $uri
     * @return mixed
     */
    private function get($uri)
    {
        return $this->request('GET', $uri);
    }

    /**
     * Make a POST request to Runcloud servers and return the response.
     *
     * @return mixed
     */
    private function post(string $uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    /**
     * Make a PUT request to Runcloud servers and return the response.
     *
     * @return mixed
     */
    private function put(string $uri, array $payload = [])
    {
        return $this->request('PUT', $uri, $payload);
    }

    /**
     * Make a PATCH request to Runcloud servers and return the response.
     *
     * @return mixed
     */
    private function patch(string $uri, array $payload = [])
    {
        return $this->request('PATCH', $uri, $payload);
    }

    /**
     * Make a DELETE request to Runcloud servers and return the response.
     *
     * @return mixed
     */
    private function delete(string $uri, array $payload = [])
    {
        return $this->request('DELETE', $uri, $payload);
    }

    /**
     * Make request to Runcloud servers and return the response.
     *
     * @return mixed
     */
    private function request(string $verb, string $uri, array $payload = [])
    {
        $response = $this->guzzle->request($verb, $uri,
            empty($payload) ? [] : ['form_params' => $payload]
        );

        //dd($this->container);

        if ($response->getStatusCode() != 200) {
            return $this->handleRequestError($response);
        }

        // $contentType = $response->getHeaders()["Content-Type"][0];
        // $contentType = $response->getHeader('Content-Type');

        // dd($contentType);

        // if ($response->header['Content-Type'] != 'application/json') {
        //     throw new ContentTypeNotManagedException();
        // }

        $responseBody = (string) $response->getBody();

        return json_decode($responseBody, true) ?: $responseBody;
    }

    /**
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return void
     */
    private function handleRequestError(ResponseInterface $response)
    {
        if ($response->getStatusCode() == 302) {
            throw new UnwantedRedirectException();
        }

        if ($response->getStatusCode() == 401) {
            throw new AuthenticationFailedException();
        }

        if ($response->getStatusCode() == 403) {
            throw new ForbiddenRequestException();
        }

        if ($response->getStatusCode() == 404) {
            throw new NotFoundException();
        }

        if ($response->getStatusCode() == 422) {
            throw new ValidationException(json_decode((string) $response->getBody(), true));
        }

        if ($response->getStatusCode() == 429) {
            throw new TooManyRequestsException();
        }

        throw new \Exception((string) $response->getBody());

    }

    /**
     * Retry the callback or fail after x seconds.
     *
     * @return mixed
     */
    public function retry(int $timeout, Callable $callback)
    {
        $start = time();

        beginning:

        if ($output = $callback()) {
            return $output;
        }

        if (time() - $start < $timeout) {
            sleep(5);

            goto beginning;
        }

        throw new TimeoutException($output);
    }
}