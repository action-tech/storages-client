<?php

declare(strict_types=1);

namespace Action\StoragesClient;

use Action\StoragesClient\ValueObject\{UserId};
use Action\StoragesClient\Exception\StoragesClientException;
use Action\StoragesClient\Dto\{AccessDto,
    AccessListDto,
    AddAccessDto,
    AddSlaveDto,
    BulkUpdateSlaveDto,
    RemoveSlaveDto,
    UserDto};

interface StoragesClientInterface
{
    /**
     * @param string $token
     * @return UserDto
     * @throws StoragesClientException
     */
    public function getUser(string $token): UserDto;

    /**
     * @param string $token
     * @return AccessListDto
     * @throws StoragesClientException
     */
    public function getUserAccess(string $token): AccessListDto;

    /**
     * @param string $token
     * @return AccessListDto
     * @throws StoragesClientException
     */
    public function getActiveUserAccess(string $token): AccessListDto;

    /**
     * @param string       $token
     * @param AddAccessDto $addAccessDto
     * @return AccessDto
     * @throws StoragesClientException
     */
    public function addDemoAccess(string $token, AddAccessDto $addAccessDto): AccessDto;

    /**
     * @param string      $token
     * @param AddSlaveDto $addSlaveDto
     * @return AccessDto
     * @throws StoragesClientException
     */
    public function addSlave(string $token, AddSlaveDto $addSlaveDto): AccessDto;

    /**
     * @param string         $token
     * @param RemoveSlaveDto $removeSlaveDto
     * @return AccessDto
     * @throws StoragesClientException
     */
    public function removeSlave(string $token, RemoveSlaveDto $removeSlaveDto): AccessDto;

    /**
     * @param string             $token
     * @param BulkUpdateSlaveDto $bulkUpdateSlaveDto
     * @return AccessDto
     * @throws StoragesClientException
     */
    public function bulkUpdateSlave(string $token, BulkUpdateSlaveDto $bulkUpdateSlaveDto): AccessDto;
}
