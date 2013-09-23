# Laravel Database

This package contain a driver to enable Mysql Clustering with Laravel 4.

### Status

[![Build Status](https://travis-ci.org/ellipsesynergie/laravel-database.png?branch=master)](https://travis-ci.org/ellipsesynergie/laravel-database)
[![Total Downloads](https://poser.pugx.org/ellipsesynergie/laravel-database/downloads.png)](https://packagist.org/packages/ellipsesynergie/laravel-database)
[![Latest Stable Version](https://poser.pugx.org/ellipsesynergie/laravel-database/v/stable.png)](https://packagist.org/packages/ellipsesynergie/laravel-database)

## Documentation

##Installation

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `ellipsesynergie/laravel-database`.

```javascript
{
    "require": {
        "ellipsesynergie/laravel-database": "dev-master"
    }
}
```

Update your packages with `composer update` or install with `composer install`.

Once this operation completes, you need to add the service provider. Open `app/config/app.php`, and add a new item to the providers array.

```php
EllipseSynergie\LaravelDatabase\LaravelDatabaseServiceProvider
```

##Configurations

To configure the package to meet your needs, you must publish the configuration in your application before you can modify them. Run this artisan command.

```bash
php artisan config:publish ellipsesynergie/laravel-database
```