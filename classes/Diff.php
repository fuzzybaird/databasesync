<?php namespace Fuzzybaird\Databasesync\Classes;

use Config;
use DB;

class Diff
{
	private $tablesWithJson;
	private $tableHash;
	function __construct()
	{

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

	public function convertJsonArray($array)
	{
		$jsonArray = [];
		foreach ($array as $key => $value) {
			// dd($value[0]->name);
			$jsonArray[$key] = json_encode($value);
		}
		$this->tablesWithJson = $jsonArray;
		return $jsonArray;
	}
}