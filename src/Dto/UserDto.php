<?php

declare(strict_types=1);

namespace Action\StoragesClient\Dto;

use Action\StoragesClient\ValueObject\UserId;

class UserDto
{
    /**
     * @var UserId
     */
    public $userId;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $emailSubmittedFlag;
    /**
     * @var string
     */
    public $phone;
    /**
     * @var string
     */
    public $phoneSubmittedFlag;
    /**
     * @var string
     */
    public $firstName;
    /**
     * @var string
     */
    public $middleName;
    /**
     * @var string
     */
    public $lastName;
    /**
     * @var string
     */
    public $gender;
    /**
     * @var string
     */
    public $birthdate;
    /**
     * @var string
     */
    public $post;
    /**
     * @var string
     */
    public $region;
    /**
     * @var string
     */
    public $branch;
    /**
     * @var string
     */
    public $company;
    /**
     * @var string
     */
    public $staff;
    /**
     * @var string
     */
    public $fillprofile;
    /**
     * @var string
     */
    public $lang;
    /**
     * @var string
     */
    public $inn;
    /**
     * @var string
     */
    public $kpp;
    /**
     * @var string
     */
    public $ogrn;
    /**
     * @var string
     */
    public $postId;
    /**
     * @var string
     */
    public $avatar;
    /**
     * @var string
     */
    public $city;
    /**
     * @var string
     */
    public $msp;
    /**
     * @var string
     */
    public $regionguid;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

}
