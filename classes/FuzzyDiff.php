<?php namespace Fuzzybaird\Databasesync\Classes;
use cogpowered\FineDiff\Render\Text;
use cogpowered\FineDiff\Diff;
use Fuzzybaird\Databasesync\Classes\treeWalker;
use Kint;
use Storage;
use Config;
use DB;

class FuzzyDiff
{

	private $tablesWithJson;

	private $tableHash;

	private $treewalker;

	private $diff;

	private $text;

	public function __construct()
	{
		$this->treewalker  = new treeWalker();
	}
	public function KeyKey($array) 
	{	
		if ($array) {
			$newarray = [];
			foreach ($array as $key => $value) {
				$newarray[$key] = $key;
			}
			return $newarray;
		} else {
			return [];
		}
	}

	public function keyValue($array)
	{	
		if ($array) {
			$newarray = [];
			foreach ($array as $key => $value) {
				$newarray[$value] = $value;
			}
			return $newarray;
		} else {
			return [];
		}
	}

	public function createHash($str)
	{
		if (gettype($str) === 'array') {
			$tableHash = [];
			$this->convertJsonArray($str);
			foreach ($this->tablesWithJson as $key => $value) {
				$tableHash[$key] = sha1($value);
			}
			$this->tableHash = $tableHash;
			return $tableHash;
		}
	}

	public function updateFolders($array)
	{
		if(!$array) return ["error"=> true, "message"=>"no folders to updated"];
		$array = $this->keyValue($array);	
		$dir = $this->keyValue(Storage::disk('fuzzybaird_databasesync')->directories('.'));
		$deletes = array_except($dir, $array);
		if ($deletes) {
			foreach ($deletes as $value) {
				Storage::disk('fuzzybaird_databasesync')->deleteDirectory($value);
			}
		}
		foreach ($array as $key => $value) {
			Storage::disk('fuzzybaird_databasesync')->makeDirectory($value);
			Storage::disk('fuzzybaird_databasesync')->makeDirectory($value.'/changes');
			Storage::disk('fuzzybaird_databasesync')->makeDirectory($value.'/states');
		}
		return $array;
	}

	public function newState($tables)
	{
		if(!$tables) return false;
		$diffs = [];
		$tableKeys = $this->KeyKey($tables);
		$updatedTables =  $this->updateFolders($tableKeys);
		foreach ($tables as $tableKey => $tableValue) {
			if (!Storage::disk('fuzzybaird_databasesync')->exists($tableKey.'/states/'.$tableKey.'.json')) {
				$current = json_encode($tableValue);
				Storage::disk('fuzzybaird_databasesync')->makeDirectory($tableKey.'/changes');
				Storage::disk('fuzzybaird_databasesync')->put($tableKey.'/states/'.$tableKey.'.json', $current);
			}
			$old = Storage::disk('fuzzybaird_databasesync')->get($tableKey.'/states/'.$tableKey.'.json');
			// $old = json_decode($old, true);

			$current = json_encode($tableValue);
			if(!$tableValue) continue;
			if($tableKey == 'system_mail_layouts') {
			$changes = $this->treewalker->getdiff(json_decode($current,true), json_decode($old, true));
			} else {
				$changes = $this->treewalker->getdiff($current, $old);
			}
			$df = json_decode($changes);
			if ($df->new || $df->removed || $df->edited) {
				$diffs[$tableKey] = ['changes' => json_decode($changes),'table'=>$tableValue];
				Storage::disk('fuzzybaird_databasesync')->put($tableKey.'/changes/'.time().'.json', $changes);
			}
			Storage::disk('fuzzybaird_databasesync')->put($tableKey.'/states/'.$tableKey.'.json', $current);
		}

		return $diffs;

	}


}