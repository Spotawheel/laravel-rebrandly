Laravel Rebrandly Package
=====================

A laravel package for Rebrandly.

For more information see [Rebrandly](https://rebrandly.com/)

## Installation ##

### Step 1: Install spotawheel/laravel-rebrandly with composer:

```
composer require spotawheel/laravel-rebrandly
```

### Step 2: Configure Rebrandly credentials

```
php artisan vendor:publish --provider="Spotawheel\Rebrandly\RebrandlyServiceProvider"
```

Add this in you **.env** file

```
REBRANDLY_API_KEY=apikey
REBRANDLY_API_URL=https://api.rebrandly.com/v1
```

## Usage ##

```php
use Rebrandly;
```

```php
/* Return number of total links */
$string = Rebrandly::countLinks();

/* Create a new short url */
$string = Rebrandly::createLink('https://www.example.com');

/* Search link by slashtag */
$object = Rebrandly::searchLink('2j8ab31');

/* Delete link by slashtag */
$bool = Rebrandly::deleteLink('2j8ab31');

/* Return account details */
$object = Rebrandly::accountDetails();
```
## Notes ##

For more information see [Rebrandly's API docs](https://developers.rebrandly.com/docs)