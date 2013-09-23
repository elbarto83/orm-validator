<?php namespace EllipseSynergie\LaravelDatabase\MysqlCluster;

use \PDO;
use Illuminate\Database\Connectors\ConnectionFactory as Factory;
use Illuminate\Database\Connectors\MySqlConnector;

/**
 * Mysql Cluster connection
 *
 * @author Ellipse Synergie <support@ellipse-synergie.com>
 */
class ConnectionFactory extends Factory  {
	
	/**
	 * Establish a PDO connection based on the configuration.
	 *
	 * @param  array   $config
	 * @param  string  $name
	 * @return \Illuminate\Database\Connection
	 */
	public function make(array $config, $name = null)
	{
		//Parse database config
		$config = $this->parseConfig($config, $name);
	
		//Create the write connector
		$pdoWrite = $this->createConnector($config['write'])->connect($config['write']);
	
		//Create and return the connection
		return $this->createConnection($config['write']['driver'], $pdoWrite, $config['write']['database'], $config['write']['prefix'], $config);
	}

	/**
	 * Create a connector instance based on the configuration.
	 *
	 * @param  array  $config
	 * @return \Illuminate\Database\Connectors\ConnectorInterface
	 */
	public function createConnector(array $config)
	{
		return new MySqlConnector;
	}
	
	/**
	 * Create a new connection instance.
	 *
	 * @param  string  $driver
	 * @param  PDO     $connection
	 * @param  string  $database
	 * @param  string  $prefix
	 * @param  array   $config
	 * @return \Illuminate\Database\Connection
	 */
	protected function createConnection($driver, PDO $connection, $database, $prefix = '', $config = null)
	{	
		if ($this->container->bound($key = "db.connection.{$driver}"))
		{
			return $this->container->make($key, array($connection, $database, $prefix, $config));
		};
		
		//Create the read connector
		$pdoRead = $this->createConnector($config['read'])->connect($config['read']);
		
		//Create the connection
		$connection = new Connection($connection, $database, $prefix, $config);
		$connection->setPdoRead($pdoRead);
	
		return $connection;
	}	
}