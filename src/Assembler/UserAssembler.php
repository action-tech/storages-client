<?php

declare(strict_types=1);

namespace Action\StoragesClient\Assembler;

use Action\StoragesClient\Dto\UserDto;
use Action\StoragesClient\ValueObject\UserId;

class UserAssembler
{
    private const PROPERTY_NAMES_LIST = [
            'post', 'region', 'branch', 'company', 'avatar', 'city', 'staff', 'regionguid', 'fillprofile', 'lang',
            'inn', 'kpp', 'msp', 'ogrn', 'postId'
        ];

    public function assemble(array $data): UserDto
    {
        $userDto = new UserDto(
            new UserId($data['id'])
        );

        $userDto->birthdate = $data['birthdate'] ?? '';
        $userDto->email = $data['email'] ?? '';
        $userDto->emailSubmittedFlag = $data['emailSubmittedFlag'] ?? '';
        $userDto->phone = $data['phone'] ?? '';
        $userDto->phoneSubmittedFlag = $data['phoneSubmittedFlag'] ?? '';
        $userDto->firstName = $data['firstName'] ?? '';
        $userDto->middleName = $data['middleName'] ?? '';
        $userDto->lastName = $data['lastName'] ?? '';
        $userDto->gender = $data['gender'] ?? '';
        $userDto->birthdate = $data['birthdate'] ?? '';

        if (array_key_exists('properties', $data) && is_array($data['properties'])) {
            foreach ($data['properties'] as $property) {
                foreach (self::PROPERTY_NAMES_LIST as $propertyName) {
                    if ($property['key'] === $propertyName) {
                        if (property_exists(UserDto::class, $propertyName)) {
                            $userDto->$propertyName =  $property['value'] ?? '';
                        }
                    }
                }
            }
        }
        return $userDto;
    }
}
