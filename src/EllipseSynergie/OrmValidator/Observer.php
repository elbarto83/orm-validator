<?php namespace EllipseSynergie\OrmValidator;

/**
 * Base observer
 *
 * @author Maxime Beaudoin <maxime.beaudoin@ellipse-synergie.com>
 */
class Observer {
	
	/**
	 * Enable caching
	 * 
	 * @var int
	 */
	public $caching;
	
	/**
	 * Time to live for caching data
	 * 
	 * @var int
	 */
	public $cacheTTL;
	
	/**
	 * Constructor
	 * 
	 * @param int $ttl Time to live for caching data
	 */
	public function _construct($caching = true, $ttl = 3600)
	{
		$this->cacheTTL = $ttl;
		$this->caching = $caching;
	}
	
	/**
	 * Creating hook
	 * 
	 * @param Eloquent $model
	 * @throws EllipseSynergie\ApiPlatform\Services\ValidateException
	 */
	public function creating($model)
	{
		//Validate before creating
		$model->validate('creating');
	}
	
	/**
	 * created hook
	 * 
	 * @param Eloquent $model
	 */
	public function created($model)
	{
		//
	}
	
	/**
	 * updating hook
	 * 
	 * @param Eloquent $model
	 * @throws EllipseSynergie\ApiPlatform\Services\ValidateException
	 */
	public function updating($model)
	{
		//Validate before creating
		$model->validate('updating');
	}
	
	/**
	 * updated hook
	 * 
	 * @param Eloquent $model
	 */
	public function updated($model)
	{
		//
	}
	
	/**
	 * saving hook
	 * 
	 * @param Eloquent $model
	 */
	public function saving($model)
	{
		//
	}
	
	/**
	 * saved hook
	 * 
	 * @param Eloquent $model
	 */
	public function saved($model)
	{
		//If the cache is enabled, put into cache
		if ($this->caching)
			Cache::section(get_class($model))->put($model->id, $model, $this->cacheTTL);
	}
	
	/**
	 * deleting hook
	 * 
	 * @param Eloquent $model
	 */
	public function deleting($model)
	{
		//
	}
	
	/**
	 * deleted hook
	 * 
	 * @param Eloquent $model
	 */
	public function deleted($model)
	{
		//If the cache is enabled, delete from cache
		if ($this->caching)
			Cache::section(get_class($model))->forget($model->id);
	}
	
	/**
	 * restoring hook
	 * 
	 * @param Eloquent $model
	 */
	public function restoring($model)
	{
		//
	}
	
	/**
	 * restored hook
	 * 
	 * @param Eloquent $model
	 */
	public function restored($model)
	{
		//If the cache is enabled, put into cache
		if ($this->caching)
			Cache::section(get_class($model))->put($model->id, $model, $this->cacheTTL);
	}
}