<?php

declare(strict_types=1);

namespace Tests\Action\StoragesClient;

use Action\StoragesClient\Dto\AccessAttributeListItemDto;
use Action\StoragesClient\Dto\AccessAttributesDto;
use Action\StoragesClient\Dto\AccessDto;
use Action\StoragesClient\Dto\AccessInfoDto;
use Action\StoragesClient\Dto\AccessListDto;
use Action\StoragesClient\Dto\AddAccessDto;
use Action\StoragesClient\Dto\AddSlaveDto;
use Action\StoragesClient\Dto\BulkUpdateSlaveDto;
use Action\StoragesClient\Dto\RemoveSlaveDto;
use Action\StoragesClient\Dto\UserDto;
use Action\StoragesClient\Dto\UtmDto;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Action\StoragesClient\StoragesClient;
use Action\StoragesClient\ValueObject\{UserId};
use Action\StoragesClient\Exception\{StoragesClientException, StoragesClientResponseParsingException};

class StoragesClientTest extends TestCase
{
    private const FAKE_BASE_URI = 'Covid-19. We survived!';
    private const FAKE_TOKEN = 'token string';

    /**
     * @dataProvider getGetUserData
     * @param Response $response
     * @param array    $expected
     * @throws StoragesClientException
     */
    public function testGetUser(string $token, Response $response, array $expected): void
    {
        $client = $this->createMockClient($response);

        $expected['exception'] && $this->expectException($expected['exception']);

        $result = $client->getUser($token);

        isset($expected['response']) && $this->assertEquals($expected['response'], $result);
    }

    /**
     * @dataProvider getGetAccessData
     *
     * @param Response $response
     * @param array    $expected
     * @throws StoragesClientException
     */
    public function testGetAccess(string $token, Response $response, array $expected): void
    {
        $client = $this->createMockClient($response);

        $expected['exception'] && $this->expectException($expected['exception']);

        $result = $client->getUserAccess($token);

        isset($expected['response']) && $this->assertEquals($expected['response'], $result) ;
    }

    /**
     * @dataProvider getGetAccessData
     * @param Response $response
     * @param array    $expected
     * @throws StoragesClientException
     */
    public function testGetActiveAccess(string $token, Response $response, array $expected): void
    {
        $client = $this->createMockClient($response);

        $expected['exception'] && $this->expectException($expected['exception']);

        $result = $client->getActiveUserAccess($token);

        isset($expected['response']) && $this->assertEquals($expected['response'], $result) ;
    }

    /**
     * @dataProvider addDemoAccessData
     *
     * @param AddAccessDto $addAccessDto
     * @param Response     $response
     * @param array        $expected
     * @throws StoragesClientException
     */
    public function testAddDemoAccess(string $token, AddAccessDto $addAccessDto, Response $response, array $expected): void
    {
        $client = $this->createMockClient($response);

        $expected['exception'] && $this->expectException($expected['exception']);

        $result = $client->addDemoAccess($token, $addAccessDto);

        isset($expected['response']) && $this->assertEquals($expected['response'], $result) ;
    }

    /**
     * @dataProvider addSlaveData
     *
     * @param AddSlaveDto $addSlaveDto
     * @param Response    $response
     * @param array       $expected
     * @throws StoragesClientException
     */
    public function testAddSlaveAccess(string $token, AddSlaveDto $addSlaveDto, Response $response, array $expected): void
    {
        $client = $this->createMockClient($response);

        $expected['exception'] && $this->expectException($expected['exception']);

        $result = $client->addSlave($token, $addSlaveDto);

        isset($expected['response']) && $this->assertEquals($expected['response'], $result) ;
    }

    /**
     * @dataProvider removeSlaveData
     *
     * @param RemoveSlaveDto $removeSlaveDto
     * @param Response       $response
     * @param array          $expected
     * @throws StoragesClientException
     */
    public function testRemoveSlaveAccess(string $token, RemoveSlaveDto $removeSlaveDto, Response $response, array $expected): void
    {
        $client = $this->createMockClient($response);

        $expected['exception'] && $this->expectException($expected['exception']);

        $result = $client->removeSlave($token, $removeSlaveDto);

        isset($expected['response']) && $this->assertEquals($expected['response'], $result) ;
    }

    /**
     * @dataProvider bulkUpdateSlaveData
     *
     * @param BulkUpdateSlaveDto $bulkUpdateSlaveDto
     * @param Response           $response
     * @param array              $expected
     * @throws StoragesClientException
     */
    public function testBulkUpdateSlaveAccess(string $token, BulkUpdateSlaveDto $bulkUpdateSlaveDto, Response $response, array $expected): void
    {
        $client = $this->createMockClient($response);

        $expected['exception'] && $this->expectException($expected['exception']);

        $result = $client->bulkUpdateSlave($token, $bulkUpdateSlaveDto);

        isset($expected['response']) && $this->assertEquals($expected['response'], $result) ;
    }


    private function createMockClient(Response $response): StoragesClient
    {
        $mock = new MockHandler([$response]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);

        return new StoragesClient($client, self::FAKE_BASE_URI);
    }
    private function createAccess(): AccessDto
    {
        $attributes = new AccessAttributesDto([
            new AccessAttributeListItemDto('use', '3'),
            new AccessAttributeListItemDto('crm-account-pin', '3881695101'),
            new AccessAttributeListItemDto('LicenseCount', '0'),
        ]);
        $attributes->activateDate = "2019-05-22T00:00:00.000Z";
        $attributes->userCount = 1;
        $attributes->dsgNumber = 0;
        $attributes->supportDealer = null;
        $attributes->salesDealer = null;
        $attributes->salesDate = "2019-05-22T00:00:00.000Z";

        $access = new AccessDto('ab8bc96a-6d93-4852-8409-0eaabb50e81b', new UserId(1), $attributes);

        $access->productVersion = 4219;
        $access->simpleProduct = "БДАЗ06";
        $access->baseProduct = "7447";
        $access->codeValue = "9247-5942-1660-3965-7199";
        $access->accessType = "DemoAccess";
        $access->dateStart = "2019-05-22T00:00:00.000Z";
        $access->dateEnd = "2040-01-01T23:59:59.000Z";
        $access->isActive = true;
        $access->isSlave = false;
        $access->slaveIds = [];
        $access->createDate = "2019-05-22T18:18:33.710Z";

        return $access;
    }
    private function createAccessListDto(): AccessListDto
    {
        $access = $this->createAccess();
        return new AccessListDto([$access]);
    }
    public function getGetUserData(): array
    {
        $userDto = new UserDto(
            new UserId(1)
        );
        $userDto->email = "oleg@action-media.ru";
        $userDto->emailSubmittedFlag = true;
        $userDto->phone = "79999999999";
        $userDto->phoneSubmittedFlag = true;
        $userDto->firstName = "Олег";
        $userDto->middleName = "Иванович";
        $userDto->lastName = "Шустрый";
        $userDto->gender = "male";
        $userDto->birthdate = "1900-09-18T00:00:00";
        $userDto->post = "Сотрудник";
        $userDto->region = "77";
        $userDto->branch = "CA4E6BC1-BBBE-E511-8E6E-78E3B502DA44";
        $userDto->company = "ООО \"АКТИОН\"";
        $userDto->avatar = "1a26ae9a-944e-4d5d-8717-93f74eba3e56.png";
        $userDto->city = "Москва";
        $userDto->staff = "1";
        $userDto->regionguid = "990ca3f4-0659-44b0-a98c-6db8c66a990d";
        $userDto->fillprofile = "22";
        $userDto->lang = "ru-ru";
        $userDto->inn = "0123456789";
        $userDto->kpp = "0123456789";
        $userDto->msp = "false";
        $userDto->ogrn = "1234567890123";
        $userDto->postId = "ea4ecea6-e386-e611-a66e-78e3b502da44";

        return [
            'correct' => [
                'token' => self::FAKE_TOKEN,
                'response' => new Response(200, [], '
                {
                  "id": 1,
                  "email": "oleg@action-media.ru",
                  "emailSubmittedFlag": true,
                  "phone": "79999999999",
                  "phoneSubmittedFlag": true,
                  "firstName": "Олег",
                  "middleName": "Иванович",
                  "lastName": "Шустрый",
                  "gender": "male",
                  "birthdate": "1900-09-18T00:00:00",
                  "properties": [
                    {
                      "key": "post",
                      "value": "Сотрудник"
                    },
                    {
                      "key": "region",
                      "value": "77"
                    },
                    {
                      "key": "branch",
                      "value": "CA4E6BC1-BBBE-E511-8E6E-78E3B502DA44"
                    },
                    {
                      "key": "company",
                      "value": "ООО \"АКТИОН\""
                    },
                    {
                      "key": "avatar",
                      "value": "1a26ae9a-944e-4d5d-8717-93f74eba3e56.png"
                    },
                    {
                      "key": "city",
                      "value": "Москва"
                    },
                    {
                      "key": "staff",
                      "value": "1"
                    },
                    {
                      "key": "regionguid",
                      "value": "990ca3f4-0659-44b0-a98c-6db8c66a990d"
                    },
                    {
                      "key": "fillprofile",
                      "value": "22"
                    },
                    {
                      "key": "lang",
                      "value": "ru-ru"
                    },
                    {
                      "key": "inn",
                      "value": "0123456789"
                    },
                    {
                      "key": "kpp",
                      "value": "0123456789"
                    },
                    {
                      "key": "msp",
                      "value": "false"
                    },
                    {
                      "key": "ogrn",
                      "value": "1234567890123"
                    },
                    {
                      "key": "postId",
                      "value": "ea4ecea6-e386-e611-a66e-78e3b502da44"
                    }
                  ],
                  "updateDate": "2021-06-11T11:54:39"
                }
                '),
                'expected' => [
                    'exception' => null,
                    'response' => $userDto,
                ],
            ],
            'bad response' => [
                'token' => self::FAKE_TOKEN,
                'response' => new Response(400, [], 'tra-ta-ta'),
                'expected' => [
                    'exception' => StoragesClientException::class,
                    'response' => null,
                ]
            ],
            'bad json response' => [
                'token' => self::FAKE_TOKEN,
                'response' => new Response(200, [], 'tra-ta-ta'),
                'expected' => [
                    'exception' => StoragesClientResponseParsingException::class,
                    'response' => null,
                ]
            ],
            'internal server error' => [
                'token' => self::FAKE_TOKEN,
                'response' => new Response(500),
                'expected' => [
                    'exception' => StoragesClientException::class,
                    'response' => null,
                ]
            ],
        ];
    }
    public function getGetAccessData(): array
    {
        $accessListDto = $this->createAccessListDto();

        return [
            'correct' => [
                'token' => self::FAKE_TOKEN,
                'response' => new Response(200, [], '
{
  "accesses": [
    {
      "id": "ab8bc96a-6d93-4852-8409-0eaabb50e81b",
      "userId": 1,
      "productVersion": 4219,
      "simpleProduct": "БДАЗ06",
      "baseProduct": "7447",
      "codeValue": "9247-5942-1660-3965-7199",
      "accessType": "DemoAccess",
      "dateStart": "2019-05-22T00:00:00.000Z",
      "dateEnd": "2040-01-01T23:59:59.000Z",
      "isActive": true,
      "isSlave": false,
      "slaveIds": [],
      "createDate": "2019-05-22T18:18:33.710Z",
      "attributes": {
        "ActivateDate": "2019-05-22T00:00:00.000Z",
        "UserCount": 1,
        "DsgNumber": 0,
        "SupportDealer": null,
        "SalesDealer": null,
        "SalesDate": "2019-05-22T00:00:00.000Z",
        "AttributeList": [
          {
            "key": "use",
            "value": "3"
          },
          {
            "key": "crm-account-pin",
            "value": "3881695101"
          },
          {
            "key": "LicenseCount",
            "value": "0"
          }
        ]
      }
    }
  ]
}
                '),
                'expected' => [
                    'exception' => null,
                    'response' => $accessListDto,
                ],
            ],
            'bad response' => [
                'token' => self::FAKE_TOKEN,
                'response' => new Response(400, [], 'tra-ta-ta'),
                'expected' => [
                    'exception' => StoragesClientException::class,
                    'response' => null,
                ]
            ],
            'bad json response' => [
                'token' => self::FAKE_TOKEN,
                'response' => new Response(200, [], 'tra-ta-ta'),
                'expected' => [
                    'exception' => StoragesClientResponseParsingException::class,
                    'response' => null,
                ]
            ],
            'internal server error' => [
                'token' => self::FAKE_TOKEN,
                'response' => new Response(500),
                'expected' => [
                    'exception' => StoragesClientException::class,
                    'response' => null,
                ]
            ],
        ];
    }
    public function addDemoAccessData(): array
    {
        $addAccessDto = new AddAccessDto(2, "2021-06-17T07:24:39.915Z", "2022-06-17T07:24:39.915Z", 3);

        $accessInfoDto = new AccessInfoDto();
        $accessInfoDto->purpose = "for test";
        $accessInfoDto->subscriptionStatus = "subscriptionStatus";
        $accessInfoDto->sourceDomain = "sourceDomain";
        $accessInfoDto->supportDealer = 1;
        $accessInfoDto->salesDealer = 5;
        $accessInfoDto->salesDate = "2023-06-17T07:24:39.915Z";

        $utmDto = new UtmDto();
        $utmDto->utmSource = "utmSource";
        $utmDto->utmMedium = "utmMedium";
        $utmDto->utmCampaign = "utmCampaign";
        $utmDto->utmContent = "utmContent";
        $utmDto->utmTerm = "utmTerm";
        $utmDto->utmZ = "utmZ";

        $addAccessDto->accessInfo = $accessInfoDto;
        $addAccessDto->utm = $utmDto;

        $accessDto = $this->createAccess();
        $accessDto->dateStart = "2021-06-17T07:24:39.915Z";
        $accessDto->dateEnd = "2022-06-17T07:24:39.915Z";
        $accessDto->productVersion = 2;
        $accessDto->attributes->userCount = 3;
        $accessDto->attributes->supportDealer = "1";
        $accessDto->attributes->salesDealer = "5";
        $accessDto->attributes->salesDate = "2023-06-17T07:24:39.915Z";
        $accessDto->attributes->attributeList = [
            new AccessAttributeListItemDto("source-domain", "source-domain"),
            new AccessAttributeListItemDto("UtmSource", "utmSource"),
            new AccessAttributeListItemDto("UtmMedium", "utmMedium"),
            new AccessAttributeListItemDto("UtmCampaign", "utmCampaign"),
            new AccessAttributeListItemDto("UtmContent", "utmContent"),
            new AccessAttributeListItemDto("UtmTerm", "utmTerm"),
            new AccessAttributeListItemDto("UtmZ", "utmZ"),
            new AccessAttributeListItemDto("Purpose", "for test"),
            new AccessAttributeListItemDto("SubscriptionStatus", "subscriptionStatus"),
        ];

        return [
            'correct' => [
                'token' => self::FAKE_TOKEN,
                'addAccessDto' => $addAccessDto,
                'response' => new Response(200, [], '
{
  "id": "ab8bc96a-6d93-4852-8409-0eaabb50e81b",
  "userId": 1,
  "productVersion": 2,
  "simpleProduct": "БДАЗ06",
  "baseProduct": "7447",
  "codeValue": "9247-5942-1660-3965-7199",
  "accessType": "DemoAccess",
  "dateStart": "2021-06-17T07:24:39.915Z",
  "dateEnd": "2022-06-17T07:24:39.915Z",
  "isActive": true,
  "isSlave": false,
  "slaveIds": [],
  "createDate": "2019-05-22T18:18:33.710Z",
  "attributes": {
    "ActivateDate": "2019-05-22T00:00:00.000Z",
    "UserCount": 3,
    "DsgNumber": 0,
    "SupportDealer": "1",
    "SalesDealer": "5",
    "SalesDate": "2023-06-17T07:24:39.915Z",
    "AttributeList": [
      {
        "key": "source-domain",
        "value": "source-domain"
      },
      {
        "key": "UtmSource",
        "value": "utmSource"
      },
      {
        "key": "UtmMedium",
        "value": "utmMedium"
      },
      {
        "key": "UtmCampaign",
        "value": "utmCampaign"
      },
      {
        "key": "UtmContent",
        "value": "utmContent"
      },
      {
        "key": "UtmTerm",
        "value": "utmTerm"
      },
      {
        "key": "UtmZ",
        "value": "utmZ"
      },
      {
        "key": "Purpose",
        "value": "for test"
      },
      {
        "key": "SubscriptionStatus",
        "value": "subscriptionStatus"
      }
    ]
  }
}
                '),
                'expected' => [
                    'exception' => null,
                    'response' => $accessDto,
                ],
            ],
            'bad response' => [
                'token' => self::FAKE_TOKEN,
                'addAccessDto' => $addAccessDto,
                'response' => new Response(400, [], 'tra-ta-ta'),
                'expected' => [
                    'exception' => StoragesClientException::class,
                    'response' => null,
                ]
            ],
            'bad json response' => [
                'token' => self::FAKE_TOKEN,
                'addAccessDto' => $addAccessDto,
                'response' => new Response(200, [], 'tra-ta-ta'),
                'expected' => [
                    'exception' => StoragesClientResponseParsingException::class,
                    'response' => null,
                ]
            ],
            'internal server error' => [
                'token' => self::FAKE_TOKEN,
                'addAccessDto' => $addAccessDto,
                'response' => new Response(500),
                'expected' => [
                    'exception' => StoragesClientException::class,
                    'response' => null,
                ]
            ],
        ];
    }
    public function addSlaveData(): array
    {
        $addSlaveDto = new AddSlaveDto("0dd10d3f-3064-4a50-921e-57475048aab4", 5818381);
        $accessDto = $this->createAccess();
        $accessDto->id = "0dd10d3f-3064-4a50-921e-57475048aab4";
        $accessDto->slaveIds = [5818381];

        return [
            'correct' => [
                'token' => self::FAKE_TOKEN,
                'addSlaveDto' => $addSlaveDto,
                'response' => new Response(200, [], '
    {
      "id": "0dd10d3f-3064-4a50-921e-57475048aab4",
      "userId": 1,
      "productVersion": 4219,
      "simpleProduct": "БДАЗ06",
      "baseProduct": "7447",
      "codeValue": "9247-5942-1660-3965-7199",
      "accessType": "DemoAccess",
      "dateStart": "2019-05-22T00:00:00.000Z",
      "dateEnd": "2040-01-01T23:59:59.000Z",
      "isActive": true,
      "isSlave": false,
      "slaveIds": [
        5818381
       ],
      "createDate": "2019-05-22T18:18:33.710Z",
      "attributes": {
        "ActivateDate": "2019-05-22T00:00:00.000Z",
        "UserCount": 1,
        "DsgNumber": 0,
        "SupportDealer": null,
        "SalesDealer": null,
        "SalesDate": "2019-05-22T00:00:00.000Z",
        "AttributeList": [
          {
            "key": "use",
            "value": "3"
          },
          {
            "key": "crm-account-pin",
            "value": "3881695101"
          },
          {
            "key": "LicenseCount",
            "value": "0"
          }
        ]
      }
    }
                '),
                'expected' => [
                    'exception' => null,
                    'response' => $accessDto,
                ],
            ],
            'bad response' => [
                'token' => self::FAKE_TOKEN,
                'addSlaveDto' => $addSlaveDto,
                'response' => new Response(400, [], 'tra-ta-ta'),
                'expected' => [
                    'exception' => StoragesClientException::class,
                    'response' => null,
                ]
            ],
            'bad json response' => [
                'token' => self::FAKE_TOKEN,
                'addSlaveDto' => $addSlaveDto,
                'response' => new Response(200, [], 'tra-ta-ta'),
                'expected' => [
                    'exception' => StoragesClientResponseParsingException::class,
                    'response' => null,
                ]
            ],
            'internal server error' => [
                'token' => self::FAKE_TOKEN,
                'addSlaveDto' => $addSlaveDto,
                'response' => new Response(500),
                'expected' => [
                    'exception' => StoragesClientException::class,
                    'response' => null,
                ]
            ],
        ];
    }
    public function removeSlaveData(): array
    {
        $removeSlaveDto = new RemoveSlaveDto("0dd10d3f-3064-4a50-921e-57475048aab4", 5818381);
        $accessDto = $this->createAccess();
        $accessDto->id = "0dd10d3f-3064-4a50-921e-57475048aab4";

        return [
            'correct' => [
                'token' => self::FAKE_TOKEN,
                'removeSlaveDto' => $removeSlaveDto,
                'response' => new Response(200, [], '
    {
      "id": "0dd10d3f-3064-4a50-921e-57475048aab4",
      "userId": 1,
      "productVersion": 4219,
      "simpleProduct": "БДАЗ06",
      "baseProduct": "7447",
      "codeValue": "9247-5942-1660-3965-7199",
      "accessType": "DemoAccess",
      "dateStart": "2019-05-22T00:00:00.000Z",
      "dateEnd": "2040-01-01T23:59:59.000Z",
      "isActive": true,
      "isSlave": false,
      "slaveIds": [],
      "createDate": "2019-05-22T18:18:33.710Z",
      "attributes": {
        "ActivateDate": "2019-05-22T00:00:00.000Z",
        "UserCount": 1,
        "DsgNumber": 0,
        "SupportDealer": null,
        "SalesDealer": null,
        "SalesDate": "2019-05-22T00:00:00.000Z",
        "AttributeList": [
          {
            "key": "use",
            "value": "3"
          },
          {
            "key": "crm-account-pin",
            "value": "3881695101"
          },
          {
            "key": "LicenseCount",
            "value": "0"
          }
        ]
      }
    }
                '),
                'expected' => [
                    'exception' => null,
                    'response' => $accessDto,
                ],
            ],
            'bad response' => [
                'token' => self::FAKE_TOKEN,
                'removeSlaveDto' => $removeSlaveDto,
                'response' => new Response(400, [], 'tra-ta-ta'),
                'expected' => [
                    'exception' => StoragesClientException::class,
                    'response' => null,
                ]
            ],
            'bad json response' => [
                'token' => self::FAKE_TOKEN,
                'removeSlaveDto' => $removeSlaveDto,
                'response' => new Response(200, [], 'tra-ta-ta'),
                'expected' => [
                    'exception' => StoragesClientResponseParsingException::class,
                    'response' => null,
                ]
            ],
            'internal server error' => [
                'token' => self::FAKE_TOKEN,
                'removeSlaveDto' => $removeSlaveDto,
                'response' => new Response(500),
                'expected' => [
                    'exception' => StoragesClientException::class,
                    'response' => null,
                ]
            ],
        ];
    }
    public function bulkUpdateSlaveData(): array
    {
        $bulkUpdateSlaveDto = new BulkUpdateSlaveDto("0dd10d3f-3064-4a50-921e-57475048aab4", [5818381]);
        $accessDto = $this->createAccess();
        $accessDto->id = "0dd10d3f-3064-4a50-921e-57475048aab4";
        $accessDto->slaveIds = [5818381];

        return [
            'correct' => [
                'token' => self::FAKE_TOKEN,
                'bulkUpdateSlaveDto' => $bulkUpdateSlaveDto,
                'response' => new Response(200, [], '
    {
      "id": "0dd10d3f-3064-4a50-921e-57475048aab4",
      "userId": 1,
      "productVersion": 4219,
      "simpleProduct": "БДАЗ06",
      "baseProduct": "7447",
      "codeValue": "9247-5942-1660-3965-7199",
      "accessType": "DemoAccess",
      "dateStart": "2019-05-22T00:00:00.000Z",
      "dateEnd": "2040-01-01T23:59:59.000Z",
      "isActive": true,
      "isSlave": false,
      "slaveIds": [
        5818381
       ],
      "createDate": "2019-05-22T18:18:33.710Z",
      "attributes": {
        "ActivateDate": "2019-05-22T00:00:00.000Z",
        "UserCount": 1,
        "DsgNumber": 0,
        "SupportDealer": null,
        "SalesDealer": null,
        "SalesDate": "2019-05-22T00:00:00.000Z",
        "AttributeList": [
          {
            "key": "use",
            "value": "3"
          },
          {
            "key": "crm-account-pin",
            "value": "3881695101"
          },
          {
            "key": "LicenseCount",
            "value": "0"
          }
        ]
      }
    }
                '),
                'expected' => [
                    'exception' => null,
                    'response' => $accessDto,
                ],
            ],
            'bad response' => [
                'token' => self::FAKE_TOKEN,
                'bulkUpdateSlaveDto' => $bulkUpdateSlaveDto,
                'response' => new Response(400, [], 'tra-ta-ta'),
                'expected' => [
                    'exception' => StoragesClientException::class,
                    'response' => null,
                ]
            ],
            'bad json response' => [
                'token' => self::FAKE_TOKEN,
                'bulkUpdateSlaveDto' => $bulkUpdateSlaveDto,
                'response' => new Response(200, [], 'tra-ta-ta'),
                'expected' => [
                    'exception' => StoragesClientResponseParsingException::class,
                    'response' => null,
                ]
            ],
            'internal server error' => [
                'token' => self::FAKE_TOKEN,
                'bulkUpdateSlaveDto' => $bulkUpdateSlaveDto,
                'response' => new Response(500),
                'expected' => [
                    'exception' => StoragesClientException::class,
                    'response' => null,
                ]
            ],
        ];
    }
}
