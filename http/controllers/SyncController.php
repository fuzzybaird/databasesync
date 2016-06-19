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
    public function index(Settings $settings, ColumnsAndTables $ColumnsAndTables,Diff $diff)
    {
    	$settingsTables = $settings->get('tables');
        if($settingsTables){
        $diff->updateFolders($settingsTables);
        
        
        } else {
            $diff->updateFolders($settingsTables);
            return ['error'=>'no tables to sync'];
        }
    }

    public function checkState(Settings $settings,ColumnsAndTables $ColumnsAndTables,Diff $diff)
    {
        $settingsTables = $settings->get('tables');
        $tables = $ColumnsAndTables->selectTables($settingsTables)->fetch();
        // dd($tables);
        $diff->compareStates($tables);
        dd($tables);
        $diff->setStates($tables);
    }

}
	// dd('hello');
