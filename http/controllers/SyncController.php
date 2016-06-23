<?php namespace Fuzzybaird\Databasesync\Http\Controllers;

use Illuminate\Routing\Controller;
use Fuzzybaird\Databasesync\Classes\ColumnsAndTables;
use Fuzzybaird\Databasesync\Classes\FuzzyDiff;
use Fuzzybaird\Databasesync\Models\Settings;

class SyncController extends Controller
{
public $diff;



	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index(Settings $settings, ColumnsAndTables $ColumnsAndTables)
	{
		$settingsTables = $settings->get('tables');
		// dd('still works');
		if($settingsTables){

			$diff = new FuzzyDiff;

			$diff->updateFolders($settingsTables);

			$tables = $ColumnsAndTables->selectTables($settingsTables)->fetch();

			$diff = $diff->newState($tables);

			return [$tables, $diff];

		} else {

			$diff->updateFolders($settingsTables);

			return ['error'=>'no tables to sync'];

		}
	}

	public function checkState(Settings $settings,ColumnsAndTables $ColumnsAndTables, FuzzyDiff $diff)
	{

		$settingsTables = $settings->get('tables');

		$tables = $ColumnsAndTables->selectTables($settingsTables)->fetch();
		dd($tables);

		$newstate = $diff->newState($tables);

		dd($newstate);

	}

}
