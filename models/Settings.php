<?php namespace Fuzzybaird\DatabaseSync\Models;

use Model;
use DB;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'fuzzybaird_databasesync_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public function getTablesOptions()
    {
    	$tables = DB::select('SHOW TABLES');
    	$tablesAndColumns = [];
    	foreach ($tables as $table) {
    		$tablesAndColumns[$table->Tables_in_givinggame] = $table->Tables_in_givinggame;
    	}
    	//DB::getSchemaBuilder()->getColumnListing($table->Tables_in_givinggame)
        return $tablesAndColumns;
    }
    public function getColumnsOptions()
    {
    	$allcolumns = [];
    	if ($this->tables) {
    		foreach ($this->tables as $table) {
    			$columns = DB::getSchemaBuilder()->getColumnListing($table);
    			foreach ($columns as $column) {
    				$allcolumns[$table.':'.$column] = $table.': '.$column;
    			}
    		}
    	}

    	// dd($columns);
    	return $allcolumns;
    }
}
