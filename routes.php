<?php
// use Fuzzybaird\Databasesync\Classes\ColumnsAndTables;
use Fuzzybaird\Databasesync\Classes\Diff;
Route::get('/', 'Fuzzybaird\Databasesync\Http\Controllers\SyncController@index');