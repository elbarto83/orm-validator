<?php namespace EllipseSynergie\OrmValidator\Eloquent;

use EllipseSynergie\OrmValidator\ObserverNotFound;
use EllipseSynergie\OrmValidator\ValidationServiceNotFound;
use EllipseSynergie\OrmValidator\ValidationMethodNotFound;
use Illuminate\Validation\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * Base model
 *
 * @author Maxime Beaudoin <maxime.beaudoin@ellipse-synergie.com>
 */
abstract class BaseModel extends Model {
	
	/**
	 * The validation service class name
	 * 
	 * @var string
	 */
	protected static $validationService;
	
	/**
	 * The observer class name
	 * 
	 * @var string
	 */
	protected static $observer;
	
	/**
	 * Boot use to register event bindings
	 */
	public static function boot()
	{
		parent::boot();
		
		//Check if the observer class exist
		if (class_exists(static::$observer)) {
			
			//Create the observer
			self::observe( new static::$observer(Config::get('orm-validator::cache.caching'), Config::get('orm-validator::cache.ttl')) );
			
		//Else the observer is not found
		} else {
			throw new ObserverNotFound;
		}
	}

	/**
	 * Default validation
	 * 
	 * @param array $input
	 * @throws \EllipseSynergie\ApiPlatform\Services\ValidateException
	 */
	public function validate($action = 'creating')
	{
		
		//Check if the validation service class exist
		if (class_exists(static::$validationService)) {
			
			//Factory the validator
			$validator = $this->factoryValidator();
			
		//Else the observer is not found
		} else {
			throw new ValidationServiceNotFound;
		}
		
		#print_r($validator);
		
		//If the validation method doesn't  exist
		if (!method_exists($validator, $action)) {
			throw new ValidationMethodNotFound;
		}
		
		//Try to validate
		try
		{			
			//Validate the action
			$validator->{$action}();
		}
		
		//Catch validation rules
		catch (Services\ValidateException $e)
		{
			throw $e;
		}
	}
	
	/**
	 * Get resource by id
	 * 
	 * @param int $id
	 */
	public function getById($id)
	{
		//Find the account
		$account = self::findOrFail($id);
			
		//Put entry into the cache
		\Cache::section(get_called_class())->put($id, $account, Config::get('cache.maxtime'));
		
		return $account;
	}
	
	/**
	 * Delete resource by id
	 * 
	 * @param int $id
	 */
	public function deleteById($id)
	{
		$account = $this->getById($id);
		$account->delete();
		
		return $account;
	}
	
	/**
	 * Update resource by id
	 * 
	 * @param int $id
	 */
	public function updateById($input, $id)
	{
		$account = $this->getById($id);
		
		$account->fill($input);
		$account->save();
		
		return $account;
	}
	
	/**
	 * Get resource by id from cache
	 * 
	 * @param int $id
	 */
	public function getByIdFromCache($id)
	{
		//Get form cache
		$account = \Cache::section(get_called_class())->get($id);
		
		//If cache not empty
		if ($account) {
			
			//Return the account
			return $account;
			
		//Else not found in the cache
		} else {
			return $this->getById($id);
		}
	}
	
	/**
	 * Factory the validator
	 * 
	 * @return \EllipseSynergie\OrmValidator\Services\Validation
	 */
	protected function factoryValidator()
	{
		//Factory the validator
		$factory = new Factory(app('translator'), app());
		
		//Create the validator
		$validator = new static::$validationService($this->getAttributes(), $validatorFactory);
			
		return $validator;
	}
}