<?php namespace EllipseSynergie\LaravelDatabase;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class LaravelDatabaseServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ellipsesynergie/laravel-database');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
		DB::extend('mysql-cluster', function($config){
		
			//Create the connection factory
			$factory = new MysqlCluster\ConnectionFactory(new Illuminate\Container\Container);
		
			//Return the connection
			return $factory->make($config, 'mysql');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}