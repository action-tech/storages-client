<?php

declare(strict_types=1);

namespace Action\StoragesClient\Dto;

use Action\StoragesClient\ValueObject\UserId;

class AccessDto
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var UserId
     */
    public $userId;
    /**
     * @var string
     */
    public $productVersion;
    /**
     * @var string
     */
    public $simpleProduct;
    /**
     * @var string
     */
    public $baseProduct;
    /**
     * @var string
     */
    public $codeValue;
    /**
     * @var string
     */
    public $accessType;
    /**
     * @var string
     */
    public $dateStart;
    /**
     * @var string
     */
    public $dateEnd;
    /**
     * @var string
     */
    public $isActive;
    /**
     * @var string
     */
    public $isSlave;
    /**
     * @var string
     */
    public $createDate;
    /**
     * @var array
     */
    public $slaveIds;
    /**
     * @var AccessAttributesDto
     */
    public $attributes;

    public function __construct(string $id, UserId $userId, AccessAttributesDto $attributes)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->attributes = $attributes;
    }
}
