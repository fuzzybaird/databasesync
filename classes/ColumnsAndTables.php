<?php namespace Fuzzybaird\Databasesync\Classes;

use Config;
use DB;

/**
* Fetch and access all tables and data
*/
class ColumnsAndTables
{
	private $all;
	private $selectedTables;
	private $tablesWithData;
	
	function __construct()
	{
		$this->all = self::all();
		// dd($this->all);
	}


	/**
	 * @return Array of all tables
	 */
	public static function all()
	{
		$database = 'Tables_in_'.Config::get('database.connections.'.Config::get('database.default').'.database');
	    $tables = DB::select('SHOW TABLES');
	    $tablesAndColumns = [];
	    foreach ($tables as $table) {
	    	$tablesAndColumns[$table->$database] = DB::getSchemaBuilder()->getColumnListing($table->$database);
	    }
	    return $tablesAndColumns;
	}


	/**
	 * @return Array of all tables
	 */
	public function getAll()
	{
		return $this->all;
	}

	public function getSelectedTables()
	{
		return $this->selectedTables;	
	}

	public function getTablesWithData()
	{
		return $this->tablesWithData;
	}


	/**
	 * @param  [Array] list of tables to sync
	 * @return Tables with columns
	 */
	public function selectTables($array)
	{
		if(!$array) return $this;
		if (gettype($array) == 'string') {
			$array = [$array];
		}

		$matched = [];

		foreach ($array as $value) {
			$matched[$value] = $this->all[$value];
		}
		$this->selectedTables = $matched;
		// dd($this->selectedTables);
		return $this;
	}

	public function fetch()
	{
		if(!$this->selectedTables) return false;
		$array = [];
		foreach ($this->selectedTables as $key => $table) {
			$array[$key] = DB::table($key)->get();
		}
		// $this->tablesWithData = $array;
		return $array;
	}

}