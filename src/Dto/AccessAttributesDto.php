<?php

declare(strict_types=1);

namespace Action\StoragesClient\Dto;

class AccessAttributesDto
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $activateDate;
    /**
     * @var string
     */
    public $userCount;
    /**
     * @var string
     */
    public $dsgNumber;
    /**
     * @var string
     */
    public $supportDealer;
    /**
     * @var string
     */
    public $salesDealer;
    /**
     * @var string
     */
    public $salesDate;
    /**
     * @var AccessAttributeListItemDto[]
     */
    public $attributeList;
    /**
     * AccessAttributesDto constructor.
     * @param AccessAttributeListItemDto[] $attributeList
     */
    public function __construct(array $attributeList)
    {
        $this->attributeList = $attributeList;
    }
}
