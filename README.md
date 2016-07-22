# PHP SDK for the Fyber.com API 


**fyber-php-sdk** is a PHP SDK for [Fyber.com](http://developer.fyber.com/content/) (the most developer friendly ad monetization platform).

fyber-php-sdk is framework agnostic PHP package that can be integrated easily with Laravel 5.

## Installation

The recommended way to install this package is via `Composer`.

#### Via Composer

A. Run this composer command:

```bash
composer require mahmoudz/fyber-php-sdk:*
```


## Integrations

### Laravel:

The package comes bundled with a Service Provider for easier integration.

1) Register the service provider in your `config/app.php`:

```php
    'providers' => array(
        ...
		mahmoudz\fyberPhpSdk\FyberPhpSdkServiceProvider::class,
    ),
```
The service provider will automatically alias the `mahmoudz\fyberPhpSdk\Fyber` class, so you can easily use the `Fyber` facade anywhere in your app.

2) Publish the configuration file:

```bash
php artisan vendor:publish --provider ='mahmoudz\fyberPhpSdk\FyberPhpSdkServiceProvider'
```
This will add `config/fyber-sdk.php` to your Config directory.

## Configuration

Open `config/fyber-sdk.php` and customze the package

```php
    'api_key'         => 'z6ca24652116523516f2a9e5b7e02c96',
    'android_app_id'  => '11233',
    'ios_app_id'      => '22344',
    'web_app_id'      => '44566',
    'base_url'        => 'http://api.fyber.com/feed/',
    'api_version'     => '1',
    'response_format' => 'json',
```


Note: It's very recommended to not add your token (any sensetive data) to the config file instead reference it to a `.env` variable.


## Usage

##### With Laravel:

The easiest way is to use it is by the `Fyber` facade.

```php

$requiredData = [
    'uid'                                   => 1,
    'locale'                                => 'de',
    'device_id'                             => '2b6f22c904d137be2e2730235f5664094b831186',
    'os_version'                            => '4.1.2',
    'timestamp'                             => Carbon::now()->timestamp,
    'google_ad_id'                          => 'eff26c67f527e6817b36935c54f8cc5cc5cffac2',
    'google_ad_id_limited_tracking_enabled' => '38400000-8cf0-11bd-b23e-20b96e40000d',
];

$offers = Fyber::getOffers($requiredData, 'android'); // supported: ios, web and android
```

##### General usage:

```php
// inject `mahmoudz\fyberPhpSdk\Fyber`

$offers = $fyber->getOffers($data, 'web');
```


## Test

To run the tests, run the following command from the project folder.

```bash
$ ./vendor/bin/phpunit
```


## Credits

- [Mahmoud Zalt](https://github.com/Mahmoudz)
- [All Contributors](../../contributors)


## License

The MIT License (MIT).
