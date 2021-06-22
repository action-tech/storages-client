<?php

declare(strict_types=1);

namespace Action\StoragesClient\Dto;

class RemoveSlaveDto
{
    /**
     * @var string
     */
    public $accessId;
    /**
     * @var int
     */
    public $slaveId;

    public function __construct(string $accessId, int $slaveId)
    {
        $this->accessId = $accessId;
        $this->slaveId = $slaveId;
    }
}
