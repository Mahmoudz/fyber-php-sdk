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
    
    'offer_callback_token' => 'a2ca24652116523516f2a9e5b7e02cc3'),
```


Note: It's very recommended to not add your token (any sensetive data) to the config file instead reference it to a `.env` variable.


## Usage

### Get Offers

##### With Laravel:

The easiest way is to use it is by the `Fyber` facade.

```php

$requiredData = [
    'uid'                                   => 1,
    'locale'                                => 'en',
    'device_id'                             => '2b6f22c904d137be2e2730235f5664094b831186',
    'os_version'                            => '4.1.2',
    'timestamp'                             => 9922774499,
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

### Validate Offers Callback

```php
// inject `mahmoudz\fyberPhpSdk\Fyber`

$isValid = $this->fyber->isValidOfferCallback($request['amount'], $request['uid'], $request['_trans_id_'], $request['sid']);

if(!$isValid){
    // return "HTTP/1.0 400 Bad Request: wrong SID"
}
```


## Test

To test it from your code use the following:

```php
// create real instance from Fyber
$fyber = new Fyber();

// create another instance of Fyber and mock it
$fyberMock = Mockery::mock(Fyber::class);

// now let the function getOffers call the getOffersMock instead
$fyberMock->shouldReceive('getOffers')->once()->andReturn($fyber->getOffersMock([], ''));
```
Now when your code calls `$this->fyber->getOffers($data, $appType);` it will return the content of `fyber-php-sdk/src/offers-response.txt`.

<br>

Laravel friendly code sample:

```php
// create real instance from Fyber
$fyber = App::make(Fyber::class);

// create another instance of Fyber and mock it
$fyberToMock = App::make(Fyber::class);
$fyberMock = Mockery::mock(fyberToMock);
App::instance(fyberToMock, $fyberMock);

// now let the function getOffers call the getOffersMock instead
$fyberMock->shouldReceive('getOffers')->once()->andReturn($fyber->getOffersMock([], ''));
```
Inject `mahmoudz\fyberPhpSdk\Fyber` anywhere in the app and get it automatically mocked, this will also return the content of `fyber-php-sdk/src/offers-response.txt`.

## Credits

- [Mahmoud Zalt](https://github.com/Mahmoudz)
- [All Contributors](../../contributors)


## License

The MIT License (MIT).
