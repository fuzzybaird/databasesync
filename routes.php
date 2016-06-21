<?php
// use Fuzzybaird\Databasesync\Classes\ColumnsAndTables;
use Fuzzybaird\Databasesync\Classes\Diff;
Route::get('/fuzzybaird/databasesync/state', 'Fuzzybaird\Databasesync\Http\Controllers\SyncController@checkState');