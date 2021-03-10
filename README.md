# A1-notification-channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/a-mazalov/a1-notification-channel?style=flat-square)](https://packagist.org/packages/a-mazalov/a1-notification-channel)

Пакет добавляет возможность отправки СМС уведомлений через оператора А1 (Velcome)

* [`Доступные методы`](/src/ClientInterface.php)

## Установка

Установка через composer:

```bash
composer require a-mazalov/a1-notification-channel
```
Далее необходимо указать данные доступа.

## Конфигурация
```bash
php artisan vendor:publish --provider="A1\Channel\A1ServiceProvider" --tag="config"
```
##### Конфигурационный файл 
```php
// config/a1.php

return [
    "api_key" => env('A1_API_KEY', null),
    "api_login" => env('A1_API_LOGIN', null),
    "api_scheme" => env('A1_API_SCHEME', 'http'),
    "api_endpoint" => env('A1_API_ENDPOINT', 'http://smart-sender.a1.by/api/'),
];
```

##### Доступные ENV переменные
```php
A1_API_KEY=
A1_API_LOGIN=
A1_API_SCHEME=http
A1_API_ENDPOINT=http://smart-sender.a1.by/api/'
```

## Установка в Lumen
### Регистрация провайдера
```php
// bootstrap/app.php

$app->register(A1\Channel\A1ServiceProvider::class);

// Опционально регистрация фасада
$app->withFacades(true, [
    // ...
    'A1\Channel\A1Facade' => 'A1Client',
]);
```

## Использование
#### Пример канала уведомлений
``` php
namespace App\Notifications;

use A1\Channel\A1Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;
    
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['a1'];
    }

    // ..

    public function toA1($notifiable)
    {
        return (new A1Message)
            ->content($this->message);
    }
}
```

#### Получение номера телефона из модели

``` php
// App\Models\v1\Employee
// Так же необходимо добавить трейт для уведомлений
use Notifiable;

public function routeNotificationForA1()
{
    return $this->mtel ? '375' . $this->mtel : null;
}
```

#### Уведомление пользователя
``` php
$user->notify(new Notification())
```

#### Отправка СМС напрямую через Фасад
```php
// A1\Channel\A1Client;

A1Client::sendSms('375290001122', 'Текст сообщения');
```



### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Alexander](https://github.com/a-mazalov)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
