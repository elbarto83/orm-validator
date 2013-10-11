# Laravel Database

This package contain a base model using advanced validation service and model observer. We also provide a driver to enable Mysql Clustering with Laravel 4.

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

## Using the base model

We provide a base model using Model Observer (http://laravel.com/docs/eloquent#model-observers) and Validation Service provided in this package. 
The base model will factory the validator using the Laravel validation component (http://laravel.com/docs/validation).

The base model also extend directly the Eloquent ORM base class (http://laravel.com/docs/eloquent).

Here a sample example when you want to create a new model for your application :

```php
<?php 

use EllipseSynergie\LaravelDatabase\BaseModel as LaravelBaseModel;

class Account extends BaseModel {}
```

## Model Observer and validation

By default, the model observer `EllipseSynergie\LaravelDatabase\Observer` will automaticly validate data before creating and before updating.

You MUST create a Observer AND a Validation service for each of your model.

For example, if you have a Account model, you must create a observer in the file `app/models/Observer/Account.php`.

```php
<?php namespace Observer;

use EllipseSynergie\LaravelDatabase\Observer;

class Account extends Observer {}
```

And you must create a Validation service in the file `app/models/Validation/Account.php` with default rules.

```php
<?php namespace Validation;

use EllipseSynergie\LaravelDatabase\Services\Validation as ValidationService;

/**
 * Account Validation service
 */
class Account extends ValidationService {
	
	/**
	 * Default array of rules.
	 *
	 * @var array
	 */
	public $rules =  array(
		'user_id' => array('required', 'integer'),
		'company_name' => array('required', 'max:200')
	);
}
```


