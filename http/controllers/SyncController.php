<?php namespace Fuzzybaird\Databasesync\Http\Controllers;

use Illuminate\Routing\Controller;
use Fuzzybaird\Databasesync\Classes\ColumnsAndTables;
use Fuzzybaird\Databasesync\Classes\FuzzyDiff;
use Fuzzybaird\Databasesync\Models\Settings;
class SyncController extends Controller
{

    private $FuzzyDiff;

    public function __construct()
    {
        // $this->middleware('guest');
        // $this->$FuzzyDiff = new FuzzyDiff;
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index(Settings $settings, ColumnsAndTables $ColumnsAndTables)
    {
    	// $settingsTables = $settings->get('tables');
        $this->$FuzzyDiff = new FuzzyDiff;// 
        dd('still works');
     //    if($settingsTables){
     //        $fuzzydiff->updateFolders($settingsTables);
     //        $tables = $ColumnsAndTables->selectTables($settingsTables)->fetch();
     //        $fuzzydiff = $fuzzydiff->newState($tables);
     //        return [$tables, $fuzzydiff];
     //    } else {
     //        $fuzzydiff->updateFolders($settingsTables);
     //        return ['error'=>'no tables to sync'];
     //    }
    }

    // public function checkState(Settings $settings,ColumnsAndTables $ColumnsAndTables,Diff $fuzzydiff)
    // {
    //     $settingsTables = $settings->get('tables');
    //     $tables = $ColumnsAndTables->selectTables($settingsTables)->fetch();
    //     // dd($tables);
    //     $fuzzydiff->compareStates($tables);
    //     dd($tables);
    //     $fuzzydiff->newStates($tables);
    // }

}
	// dd('hello');
