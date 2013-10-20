<?php namespace EllipseSynergie\OrmValidator\Eloquent;

use EllipseSynergie\OrmValidator\ModelObserverNotFound;
use EllipseSynergie\OrmValidator\ValidationServiceNotFound;
use EllipseSynergie\OrmValidator\ValidationMethodNotFound;
use Illuminate\Validation\Factory;
use Illuminate\Database\Eloquent\Model;
use Config;

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
			throw new ModelObserverNotFound('Model observer ' . static::$observer . ' not found');
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
			throw new ValidationServiceNotFound('Validation service ' . static::$validationService . ' not found');
		}
		
		#print_r($validator);
		
		//If the validation method doesn't  exist
		if (!method_exists($validator, $action)) {
			throw new ValidationMethodNotFound('Validation method ' . $action . ' not found');
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
		//Find the result
		$result = self::findOrFail($id);
		
		return $result;
	}
	
	/**
	 * Delete resource by id
	 * 
	 * @param int $id
	 */
	public function deleteById($id)
	{
		$result = $this->getById($id);
		$result->delete();
		
		return $result;
	}
	
	/**
	 * Update resource by id
	 * 
	 * @param int $id
	 */
	public function updateById($input, $id)
	{
		$result = $this->getById($id);
		
		$result->fill($input);
		$result->save();
		
		return $result;
	}
	
	/**
	 * Get resource by id from cache
	 * 
	 * @param int $id
	 */
	public function getByIdFromCache($id)
	{
		//Get form cache
		$result = \Cache::section(get_called_class())->get($id);
		
		//If cache not empty
		if ($result) {
			
			//Return the result
			return $result;
			
		//Else not found in the cache
		} else {
			
			//Get by id
			$result = $this->getById($id);
			
			//Put entry into the cache
			\Cache::section(get_called_class())->put($id, $result, Config::get('cache.maxtime'));
		}
	}
	
/**
	 * Build get all query
	 */
	protected function buildGetAllQuery($filter)
	{
		//Build query
		$query = $this;
		
		//If we want to take a limit entries but we can't take more then 500 entries by request
		if	(isset($filter['take']) AND $filter['take'] < 500) {
			$query = $query->take($filter['take']);
			
		//By default, we take only 25 entries
		} else {
			$query = $query->take(25);
		}
			
		//If we want to skip entries
		if(isset($filter['skip']))
			$query = $query->take($filter['skip']);
			
		//If we want to skip entries
		if(isset($filter['orderBy']) AND isset($filter['orderByOption']))
			$query = $query->orderBy($filter['orderBy'], $filter['orderByOption']);
		
		//Return the current query state
		return $query;
	}

	/**
	 * Get all resource
	 */
	public function getAll($filter)
	{
		//Build get all query
		$query = $this->buildGetAllQuery($filter);
		
		//Return the current query state
		return $query->get();
	}
	
	/**
	 * Get all resource from cache
	 */
	public function getAllFromCache($filter = null)
	{
		//Get form cache
		$result = \Cache::section(get_called_class())->get('all:' . json_encode($filter));
		
		//If cache not empty
		if ($result) {
			
			//Return the result
			return $result;
			
		//Else not found in the cache
		} else {
			
			//Get all from database
			$results = $this->getAll($filter);
			
			//Put entry into the cache
			\Cache::section(get_called_class())->put('all:' . json_encode($filter), $results, Config::get('cache.maxtime'));
			
			return $results;
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
		$validatorFactory = new Factory(app('translator'), app());
		
		//Create the validator
		$validator = new static::$validationService($this->getAttributes(), $validatorFactory);
			
		return $validator;
	}
}