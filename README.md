# storages-client

## Начало работы

Добавляем раздел `repositories` в composer.json:

```json
{
    "repositories": [
        {"type": "git", "url": "https://github.com/action-tech/storages-client.git"}
    ]
}
```

Устанавливаем библиотеку:

```bash
composer require action/storages-client
```

## Требования

* PHP 7.2+

### Использование

```php
use Action\StoragesClient\StoragesClient;
use GuzzleHttp\Client;

$client = new StoragesClient(new Client(), 'https://api.action-media.ru');
$userAccess = $client->getUserAccess('token');
```

### Документация
https://conf.action-media.ru/pages/viewpage.action?pageId=233315120
