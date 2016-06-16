<?php
// use Fuzzybaird\Databasesync\Classes\ColumnsAndTables;
use Fuzzybaird\Databasesync\Classes\Diff;
Route::get('/test/test', 'Fuzzybaird\Databasesync\Http\Controllers\SyncController@index');
Route::get('/test/state', 'Fuzzybaird\Databasesync\Http\Controllers\SyncController@checkState');