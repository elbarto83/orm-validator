<?php namespace EllipseSynergie\LaravelDatabase;

use Illuminate\Validation\Factory;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Base model
 *
 * @author Maxime Beaudoin <maxime.beaudoin@ellipse-synergie.com>
 */
class BaseModel extends Eloquent {
	
	/**
	 * Boot use to register event bindings
	 */
	public static function boot()
    {
        parent::boot();        
        
		// Setup event bindings using observer
		$observer = '\\Observer\\' . get_called_class();
		
		//Check if the observer class exist
		if (class_exists($observer)) {
			
			//Create the observer
			self::observe(new $observer);
			
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
		//Factory the validator
		$validatorFactory = new Factory(app('translator'), app());
		
		//Create the service validation class name
		$service = "\\Validation\\" . get_called_class();
		
		//Check if the validation service class exist
		if (class_exists($service)) {
			
			//Create the validator
			$validator = new $service($this->getAttributes(), $validatorFactory);
			
		//Else the observer is not found
		} else {
			throw new ValidationServiceNotFound;
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
}