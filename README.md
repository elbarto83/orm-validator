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

## Validation Services

This package provide a validation service use to generate exception when validations fails.

### Creating your model validation service

For this exemple, i'll create a validation for the model `Account` in the file `app/models/Validation/Account.php`.


```php
<?php namespace Validation;

use EllipseSynergie\ApiPlatform\Services\Validation as ValidationService;

/**
 * Account Validation service
 */
class Account extends ValidationService {
	
	/**
	 * Default rules use by $this->validate().
	 *
	 * @var array
	 */
	public $rules =  array(
		'user_id' => array('required', 'integer'),
		'company_name' => array('required', 'max:200')
	);

	/**
	 * Validate account before creating it
	 *
	 * @throws ValidateException
	 */
	public function create()
	{		
		$this->validate();
	}

	/**
	 * Validate account before updating it
	 *
	 * @throws ValidateException
	 */
	public function update()
	{
		//Chagne the validation rules		
		$this->rules = array(
			'id' => array('required', 'integer')
		);
		
		$this->validate();
	}
}
```

### Integrate the validation into your model

Now it's time to integrate the validator into the a model. In this example, i'll use two differents validation action (create, update) and i'll put the validation method into a base model.
```php
<?php 

use Illuminate\Validation\Factory;

/**
 * Base model
 */
class BaseModel extends Eloquent {

	/**
	 * Default validation
	 * 
	 * @param array $input
	 * @throws \EllipseSynergie\ApiPlatform\Services\ValidateException
	 */
	public function validate($input, $action = 'create')
	{
		//Try to validate
		try
		{
			//Create the validator factory from Illuminate\Validation\Factory
			$validatorFactory = new Factory(app('translator'), app());
			
			//Create the Account validator
			$service = "Validation\\" . get_called_class();
			$validation = new $service($input, $validatorFactory);
			
			//If we want to update a entry
			if ($action == 'update'){
				$validator->update();
			} else {
				$validator->create();
			}
		}
		
		//Catch validation rules
		catch (Services\ValidateException $e)
		{
			throw $e;
		}
	}	
}
```

### Using validator into your model

Now when we try to validate and it fails we can catch the thrown exception and get the errors object from it.

You could take it a step further and perform the try/catch in the Account::create() method and when you catch the ValidateException simply re-throw it.

Don't forget to extend ```BaseModel```.

```php
<?php 

use Illuminate\Validation\Factory;

/**
 * Account model
 */
class Account extends BaseModel {

	/**
	 * Create a new account.
	 *
	 * @param  array  $input
	 * @throws ValidateException
	 * @return void
	 */
	public static function create($input)
	{
		try
		{
			$this->validate();
		}
		catch (ValidateException $errors)
		{
			throw $errors;
		}

		// Continue with creation of account.
	}
}
```

