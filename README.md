# Laravel Database

Self-validating, secure and smart models for Laravel 4's Eloquent ORM using Model Observer (http://laravel.com/docs/eloquent#model-observers) and advanced validation.

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

##Configurations

To configure the package to meet your needs, you must publish the configuration in your application before you can modify them. Run this artisan command.

```bash
php artisan config:publish ellipsesynergie/laravel-database
```

## Getting Started

`EllipseSynergie\LaravelDatabase\BaseModel` aims to extend the `Eloquent` base class without changing its core functionality. Since `EllipseSynergie\LaravelDatabase\BaseModel` itself is a descendant of `Illuminate\Database\Eloquent\Model`, all your `EllipseSynergie\LaravelDatabase\BaseModel` models are fully compatible with `Eloquent` and can harness the full power of Laravels awesome ORM.

To create a new `EllipseSynergie\LaravelDatabase\BaseModel` model, simply make your model class derive from the `EllipseSynergie\LaravelDatabase\BaseModel` base class.

```php
use EllipseSynergie\LaravelDatabase\BaseModel;

class Account extends BaseModel {}
```

> **Note:** You can freely *co-mingle* your plain-vanilla Eloquent models with `EllipseSynergie\LaravelDatabase\BaseModel` descendants. If a model object doesn't rely upon user submitted content and therefore doesn't require validation - you may leave the Eloquent model class as it is.

## Effortless Validation

`EllipseSynergie\LaravelDatabase\BaseModel` models use [Model Observer](http://laravel.com/docs/eloquent#model-observers), advanced Validation Service combined with the Laravel's built-in [Validator class](http://four.laravel.com/docs/validation).

### Models Observers

`EllipseSynergie\LaravelDatabase\BaseModel` automaticly register event observer on models `boot()` method. You MUST create a Observer for each of your `EllipseSynergie\LaravelDatabase\BaseModel` models. 

For example, if you have a Account model, you must create a observer in the file `app/models/Observer/Account.php` :

```php
<?php namespace Observer;

use EllipseSynergie\LaravelDatabase\Observer;

class Account extends Observer {}
```

> **Note:** You can keep the Observer *has-is* if you want. By default, the `EllipseSynergie\LaravelDatabase\Observer` will validate data on `creating` and `updating` event.

### Models Validations Services

`EllipseSynergie\LaravelDatabase\BaseModel` will automaticly use Validation Service of your model when validate data from inside the model or from the Observer. You MUST create a Validation Service for each of your `EllipseSynergie\LaravelDatabase\BaseModel` models. 

For example, if you have a Account model, you must create a validation service in the file `app/models/Validation/Account.php` :

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

## Retrieving Validation Errors

When an `EllipseSynergie\LaravelDatabase\BaseModel` model fails to validate, a `EllipseSynergie\LaravelDatabase\Services\ValidateException` is throw. You can catch the Exception like this :

```php

try
{
	$account = new Account;
	$account->user_id = 'foo';
	$account->save();
	
	//Success !!!
}
catch (EllipseSynergie\LaravelDatabase\Services\ValidateException $e)
{
	//Retrive the Illuminate\Validation\Validator object so you can use it exactly like http://laravel.com/docs/validation
	$validator = $e->getValidator();
}
```
