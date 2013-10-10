<?php namespace EllipseSynergie\LaravelDatabase;

/**
 * Base observer
 *
 * @author Maxime Beaudoin <maxime.beaudoin@ellipse-synergie.com>
 */
class Observer {
	
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
		//Put into cache
		Cache::section(get_class($model))->put($model->id, $model, Config::get('cache.maxtime'));
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
		//Put into cache
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
		//Put into cache
		Cache::section(get_class($model))->put($model->id, $model, Config::get('cache.maxtime'));
	}
}