<?php

declare(strict_types=1);

namespace Action\StoragesClient\Assembler;

use Action\StoragesClient\Dto\AccessAttributeListItemDto;
use Action\StoragesClient\Dto\AccessAttributesDto;
use Action\StoragesClient\Dto\AccessDto;
use Action\StoragesClient\Dto\AccessListDto;
use Action\StoragesClient\ValueObject\UserId;

class AccessAssembler
{
    public function assemble(array $data): AccessListDto
    {
        $accessList = [];
        if (array_key_exists('accesses', $data)) {
            foreach ($data['accesses'] as  $access) {
                $accessItem = $this->assembleAccessItem($access);
                $accessList[] = $accessItem;
            }
        }
        return new AccessListDto($accessList);
    }

    public function assembleAccessItem(array $access): AccessDto
    {
        $attributeList = [];
        foreach ($access['attributes']['AttributeList'] as $accessAttributeListItem) {
            $attributeListItem = new AccessAttributeListItemDto($accessAttributeListItem['key'], $accessAttributeListItem['value']);
            $attributeList[] = $attributeListItem;
        }

        $attributes = new AccessAttributesDto($attributeList);
        $attributes->activateDate = $access['attributes']['ActivateDate'] ?? null;
        $attributes->userCount = $access['attributes']['UserCount'] ?? null;
        $attributes->dsgNumber = $access['attributes']['DsgNumber'] ?? null;
        $attributes->supportDealer = $access['attributes']['SupportDealer'] ?? null;
        $attributes->salesDealer = $access['attributes']['SalesDealer'] ?? null;
        $attributes->salesDate = $access['attributes']['SalesDate'] ?? null;

        $accessItem = new AccessDto($access['id'], new UserId($access['userId']), $attributes);
        $accessItem->productVersion = $access['productVersion'] ?? null;
        $accessItem->simpleProduct =  $access['simpleProduct'] ?? null;
        $accessItem->baseProduct =  $access['baseProduct'] ?? null;
        $accessItem->codeValue =  $access['codeValue'] ?? null;
        $accessItem->accessType =  $access['accessType'] ?? null;
        $accessItem->dateStart =  $access['dateStart'] ?? null;
        $accessItem->dateEnd =  $access['dateEnd'] ?? null;
        $accessItem->isActive =  $access['isActive'] ?? null;
        $accessItem->isSlave =  $access['isSlave'] ?? null;
        $accessItem->slaveIds = $access['slaveIds'] ?? null;
        $accessItem->createDate =  $access['createDate'] ?? null;

        return $accessItem;
    }
}
