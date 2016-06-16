<?php namespace Fuzzybaird\DatabaseSync\Models;

use Model;
use Config;
use DB;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'fuzzybaird_databasesync_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    private $database_name;



    public function getTablesOptions()
    {

        $database_name = 'Tables_in_'.Config::get('database.connections.'.Config::get('database.default').'.database');
    	$tables = DB::select('SHOW TABLES');
    	$tables = array_flatten($tables);
        $newtables = [];
    	foreach ($tables as $key => $value) {
    		$newtables[$value->$database_name] = $value->$database_name;
    	}
        return $newtables;
    }
}
