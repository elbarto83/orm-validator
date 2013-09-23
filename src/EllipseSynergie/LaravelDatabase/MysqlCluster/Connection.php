<?php namespace EllipseSynergie\LaravelDatabase\MysqlCluster;

use Illuminate\Database\MySqlConnection;

/**
 * Mysql Cluster connection
 * 
 * @author Ellipse Synergie <support@ellipse-synergie.com>
 */
class Connection extends MySqlConnection  {

	/**
	 * The active PDO connection for READ database.
	 *
	 * @var PDO
	 */
	protected $pdoRead;
	
	/**
	 * Run a select statement against the database.
	 *
	 * @param  string  $query
	 * @param  array   $bindings
	 * @return array
	 */
	public function select($query, $bindings = array())
	{
		return $this->run($query, $bindings, function($me, $query, $bindings)
		{
			if ($me->pretending()) return array();
	
			// For select statements, we'll simply execute the query and return an array
			// of the database result set. Each element in the array will be a single
			// row from the database table, and will either be an array or objects.
			$statement = $me->getPdoRead()->prepare($query);
	
			$statement->execute($me->prepareBindings($bindings));
	
			return $statement->fetchAll($me->getFetchMode());
		});
	}

	/**
	 * Get the currently used PDO connection for READ database.
	 *
	 * @return PDO
	 */
	public function getPdoRead()
	{
		return $this->pdoRead;
	}	

	/**
	 * Set the currently used PDO connection for READ database.
	 *
	 * @return PDO
	 */
	public function setPdoRead($pdo)
	{
		$this->pdoRead = $pdo;
	}	
}