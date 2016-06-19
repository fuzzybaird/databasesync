<?php namespace Fuzzybaird\Databasesync\Classes;
use cogpowered\FineDiff\Render\Text;
use cogpowered\FineDiff\Diff;
use Storage;
use Config;
use DB;

class Diff
{
	private $tablesWithJson;
	private $tableHash;
	function __construct()
	{

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

	public function createHash($str){
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

	public function setStates($array)
	{
		foreach ($array as $key => $value) {
			// dd($key.'/states/'.time().'_'.$key);
			Storage::disk('fuzzybaird_databasesync')->put($key.'/states/'.time().'_'.$key.'.json', json_encode($value));
		}
	}

	public function compareStates($tables)
	{
		foreach ($tables as $key => $value) {

			// Diff::
			$file = Storage::disk('fuzzybaird_databasesync')->files($key.'/states');
			
			$old = Storage::disk('fuzzybaird_databasesync')->get($key.'/'.$file);
			// dd($old);
		}
	}
}