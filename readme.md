# Laravel FCM
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Total Downloads][ico-downloads]][link-downloads]

A Laravel package to sent notification using Firebase Cloud Messsaging (FCM)

### Installation
Require the `kemalnw/laravel-fcm` package in your `composer.json` and update your dependencies:

```sh
composer require kemalnw/laravel-fcm
```

## Configuration
You must publish the config file to define your firebase server key :
```sh
php artisan vendor:publish --tag="fcm"
```

This is the content of the config file published at `config/fcm.php`
```php
/**
 * Define your firebase server key
 */
return [
    'server_key' => env('FIREBASE_SERVER_KEY', ''),
];
```

## Usage
Use artisan command to create a notification:
```sh
php artisan make:notification SomeNotification
```
Change the `via` method so that it becomes:
```php
/**
 * Get the notification channels.
 *
 * @param  mixed  $notifiable
 * @return array|string
 */
public function via($notifiable)
{
    return ['fcm'];
}
```
Add method `toFcm` to your notification, and return an instance of `Fcm` Facade.
```php
use Fcm;

...

/**
 * Get the FCM representation of the notification.
 *
 * @param  mixed  $notifiable
 * @return void
 */
public function toFcm($notifiable)
{
    return Fcm::notification([
            'title' => 'Hi!',
            'body'  => 'This is my first notification.'
        ])
        ->timeToLive(604800); // 7 days in second
}
```
When sending to specific device, the notification system will automatically look for a `firebase_uid` property on your notifiable entity. You may customize which `firebase token` is used to deliver the notification by defining a `routeNotificationForFcm` method on the entity:
```php
...

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Route notifications for the FCM channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForFcm($notification)
    {
        return $this->firebase_uid;
    }
}
```
When sending to a topic, you may define so within the `toFcm` method in the notification:
```php
use Fcm;

...

/**
 * Get the FCM representation of the notification.
 *
 * @param  mixed  $notifiable
 * @return void
 */
public function toFcm($notifiable)
{
    return Fcm::notification([
            'title' => 'Hi!',
            'body'  => 'This is my first notification.'
        ])
        ->timeToLive(604800) // 7 days in second
        ->toTopic('topic-name');
}
```






[ico-version]: https://img.shields.io/packagist/v/kemalnw/laravel-fcm?style=flat-square
[ico-license]: https://img.shields.io/packagist/l/kemalnw/laravel-fcm?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/kemalnw/laravel-fcm?style=flat-square

[link-packagist]: https://packagist.org/packages/kemalnw/laravel-fcm
[link-downloads]: https://packagist.org/packages/kemalnw/laravel-fcm