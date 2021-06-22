<?php

declare(strict_types=1);

namespace Action\StoragesClient;

use Action\StoragesClient\Assembler\AccessAssembler;
use Action\StoragesClient\Assembler\UserAssembler;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\{GuzzleException, RequestException};
use Action\StoragesClient\Dto\{AccessDto,
    AccessListDto,
    AddAccessDto,
    AddSlaveDto,
    BulkUpdateSlaveDto,
    RemoveSlaveDto,
    UserDto};
use Action\StoragesClient\Exception\{StoragesClientException, StoragesClientResponseParsingException};

class StoragesClient implements StoragesClientInterface
{

    private const GET_USER_BY_ID_METHOD = 'v1/user_get-by-id';
    private const GET_USER_ACCESS_BY_ID_METHOD = 'v1/access_get-by-user-id';
    private const GET_ACTIVE_USER_ACCESS_BY_ID_METHOD = 'v1/access_get-active-by-user-id';
    private const ADD_DEMO_ACCESS_METHOD = 'v1/access-demo_add';
    private const ADD_SLAVE_METHOD = 'v1/access-slave_add';
    private const REMOVE_SLAVE_METHOD = 'v1/access-slave_remove';
    private const BULK_UPDATE_SLAVE_METHOD = 'v1/access-slave_bulk-update';

    /**
     * @var ClientInterface $client
     */
    private $client;

    /**
     * @var UserAssembler $userAssembler
     */
    private $userAssembler;

    /**
     * @var AccessAssembler $accessAssembler
     */
    private $accessAssembler;

    /**
     * @var string
     */
    private $baseUri;

    public function __construct(ClientInterface $client, string $baseUri)
    {
        $this->client = $client;
        $this->baseUri = $baseUri;
        $this->userAssembler = new UserAssembler();
        $this->accessAssembler = new AccessAssembler();
    }

    /**
     * @inheritDoc
     */
    public function getUser(string $token): UserDto
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->compileUserAccessUrl(static::GET_USER_BY_ID_METHOD),
                ['headers' => ['authorization' => $token]]
            );
        } catch (GuzzleException $e) {
            throw $this->convertException($e);
        }

        $result = $this->decodeResponse($response->getBody()->getContents());

        return $this->userAssembler->assemble($result);
    }

    /**
     * @inheritDoc
     */
    public function getUserAccess(string $token): AccessListDto
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->compileUrl(static::GET_USER_ACCESS_BY_ID_METHOD),
                ['headers' => ['authorization' => $token]]
            );
        } catch (GuzzleException $e) {
            throw $this->convertException($e);
        }

        $result = $this->decodeResponse($response->getBody()->getContents());

        return $this->accessAssembler->assemble($result);
    }

    /**
     * @inheritDoc
     */
    public function getActiveUserAccess(string $token): AccessListDto
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->compileUrl(static::GET_ACTIVE_USER_ACCESS_BY_ID_METHOD),
                ['headers' => ['authorization' => $token]]
            );
        } catch (GuzzleException $e) {
            throw $this->convertException($e);
        }

        $result = $this->decodeResponse($response->getBody()->getContents());

        return $this->accessAssembler->assemble($result);
    }

    /**
     * @inheritDoc
     */
    public function addDemoAccess(string $token, AddAccessDto $addAccessDto): AccessDto
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->compileUrl(static::ADD_DEMO_ACCESS_METHOD),
                ['json' =>  array_filter((array)$addAccessDto), 'headers' => ['authorization' => $token]]
            );
        } catch (GuzzleException $e) {
            throw $this->convertException($e);
        }

        $result = $this->decodeResponse($response->getBody()->getContents());

        return $this->accessAssembler->assembleAccessItem($result);
    }

    /**
     * @inheritDoc
     */
    public function addSlave(string $token, AddSlaveDto $addSlaveDto): AccessDto
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->compileUrl(static::ADD_SLAVE_METHOD),
                ['json' =>  array_filter((array)$addSlaveDto), 'headers' => ['authorization' => $token]]
            );
        } catch (GuzzleException $e) {
            throw $this->convertException($e);
        }

        $result = $this->decodeResponse($response->getBody()->getContents());

        return $this->accessAssembler->assembleAccessItem($result);
    }

    /**
     * @inheritDoc
     */
    public function removeSlave(string $token, RemoveSlaveDto $removeSlaveDto): AccessDto
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->compileUrl(static::REMOVE_SLAVE_METHOD),
                ['json' =>  array_filter((array)$removeSlaveDto), 'headers' => ['authorization' => $token]]
            );
        } catch (GuzzleException $e) {
            throw $this->convertException($e);
        }

        $result = $this->decodeResponse($response->getBody()->getContents());

        return $this->accessAssembler->assembleAccessItem($result);
    }

    /**
     * @inheritDoc
     */
    public function bulkUpdateSlave(string $token, BulkUpdateSlaveDto $bulkUpdateSlaveDto): AccessDto
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->compileUrl(static::BULK_UPDATE_SLAVE_METHOD),
                ['json' =>  array_filter((array)$bulkUpdateSlaveDto), 'headers' => ['authorization' => $token]]
            );
        } catch (GuzzleException $e) {
            throw $this->convertException($e);
        }

        $result = $this->decodeResponse($response->getBody()->getContents());

        return $this->accessAssembler->assembleAccessItem($result);
    }

    private function compileUserAccessUrl(string $method): string
    {
        return rtrim($this->baseUri, '/') . '/storages/external-users-proxy/api/' . $method;
    }

    private function compileUrl(string $method): string
    {
        return rtrim($this->baseUri, '/') . '/storages/external-access-proxy/api/' . $method;
    }

    /**
     * @param string $response
     * @return array
     * @throws StoragesClientResponseParsingException
     */
    private function decodeResponse(string $response): array
    {
        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new StoragesClientResponseParsingException();
        }

        return $data;
    }

    /**
     * @param GuzzleException $exception
     * @return StoragesClientException
     */
    private function convertException(GuzzleException $exception): StoragesClientException
    {
        $message = 'GuzzleException: '.$exception->getMessage();
        $code = $exception->getCode();

        if ($exception instanceof RequestException) {
            $response = $exception->getResponse();

            if ($response) {
                $body = json_decode($response->getBody()->getContents(), true);

                $message = $body[0]['message'] ?? $message;
            }
        }

        return new StoragesClientException($message, (int) $code, $exception);
    }
}
