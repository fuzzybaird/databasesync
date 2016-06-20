<?php namespace Fuzzybaird\Databasesync\Classes;
use cogpowered\FineDiff\Render\Text;
use cogpowered\FineDiff\Diff;
use Kint;
use Storage;
use Config;
use DB;

class FuzzyDiff
{
	private $tablesWithJson;
	private $tableHash;



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
		// dd('test');
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
			Storage::disk('fuzzybaird_databasesync')->makeDirectory($value.'/fowards');
			Storage::disk('fuzzybaird_databasesync')->makeDirectory($value.'/backwards');
			Storage::disk('fuzzybaird_databasesync')->makeDirectory($value.'/states');
		}
		return $array;
	}



	public function newState($tables)
	{
		if(!$tables) return false;
		$diffs = [];

		foreach ($tables as $tableKey => $tableValue) {
// dd(Storage::disk('fuzzybaird_databasesync')->exists($tableKey.'/states/'.$tableKey.'.json'),$tableKey);
			if (!Storage::disk('fuzzybaird_databasesync')->exists($tableKey.'/states/'.$tableKey.'.json')) {
				dd('test');
				$current = json_encode($tableValue);
				Storage::disk('fuzzybaird_databasesync')->put($tableKey.'/states/'.$tableKey.'.json', $current);
			} else {

				$diff = new Diff;

				$old = Storage::disk('fuzzybaird_databasesync')->get($tableKey.'/states/'.$tableKey.'.json');
				
				$current = json_encode($tableValue);
				
				$Opcodes = $diff->getOpcodes($old, $current);
				dd(json_decode($Opcodes));
				//  Kint::enabled(true);
				if ($Opcodes) {
					$diffs[$tableKey] = $Opcodes;
				}
				Storage::disk('fuzzybaird_databasesync')->put($tableKey.'/states/'.$tableKey.'.json', $current);
			}
		}
		// dd($diffs);
	}



	// public function compareStates($tables)
	// {
	// 	foreach ($tables as $key => $value) {

	// 		$file = Storage::disk('fuzzybaird_databasesync')->files($key.'/states');
	// 		if(!$file) return false;
	// 		$old = Storage::disk('fuzzybaird_databasesync')->get($key.'/'.$file);
	// 		dd($old);
	// 	}
	// }
}