<?php namespace Fuzzybaird\Databasesync\Http\Controllers;

use Illuminate\Routing\Controller;
use Fuzzybaird\Databasesync\Classes\ColumnsAndTables;
use Fuzzybaird\Databasesync\Classes\Diff;
use Fuzzybaird\Databasesync\Models\Settings;
class SyncController extends Controller
{



    public function __construct()
    {
        // $this->middleware('guest');
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
    	$settingsTables = with(new Settings)->get('tables');
    	$settingsTables = array_shift($settingsTables);
        $stuff = new ColumnsAndTables;
        $tables = $stuff->selectTables($settingsTables)->fetchData()->getTablesWithData();
        $diff = new Diff;
        dd($diff->convertJsonArray($tables));
        return $tables;
    }

}
	// dd('hello');
