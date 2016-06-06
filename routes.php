<?php
Route::get('/', function () {
    $tables = DB::select('SHOW TABLES');
    $tablesAndColumns = [];
    foreach ($tables as $table) {
    	$tablesAndColumns[$table->Tables_in_givinggame] = DB::getSchemaBuilder()->getColumnListing($table->Tables_in_givinggame);
    }
    dd($tablesAndColumns);
});