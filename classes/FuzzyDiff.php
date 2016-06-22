<?php namespace Fuzzybaird\Databasesync\Classes;
use cogpowered\FineDiff\Granularity\Character;
use cogpowered\FineDiff\Granularity\Sentence;
use cogpowered\FineDiff\Granularity\Comma;
use cogpowered\FineDiff\Granularity\Word;
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

		$granularity = new Word;
		$this->treewalker  = new treeWalker(['debug'=>true, 'returntype'=>'jsonstring']);
		$this->diff        = new Diff($granularity);
		$this->text        = new Text;

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

		$tableKeys = $this->KeyKey($tables);

		$updatedTables =  $this->updateFolders($tableKeys);

		foreach ($tables as $tableKey => $tableValue) {

			if (!Storage::disk('fuzzybaird_databasesync')->exists($tableKey.'/states/'.$tableKey.'.json')) {

				$current = json_encode($tableValue);

				Storage::disk('fuzzybaird_databasesync')->makeDirectory($tableKey.'/fowards');

				Storage::disk('fuzzybaird_databasesync')->makeDirectory($tableKey.'/backwards');

				Storage::disk('fuzzybaird_databasesync')->put($tableKey.'/states/'.$tableKey.'.json', $current);

			}

			$old = Storage::disk('fuzzybaird_databasesync')->get($tableKey.'/states/'.$tableKey.'.json');

			$old = json_decode($old);

			$current = $tableValue;

			$fowardOpcodes = $this->treewalker->getdiff($old, $old);

			$backwardsOpcodes = $this->treewalker->getdiff($old, $current);

			dd($fowardOpcodes, $backwardsOpcodes);

			$render = $this->text;

			$change = $render->process($current, $backwardsOpcodes);

			if ($fowardOpcodes) {

				$diffs[$tableKey] = ['foward'=>json_encode($fowardOpcodes),'backwards'=>json_encode($backwardsOpcodes)];

			}

			// Storage::disk('fuzzybaird_databasesync')->put($tableKey.'/states/'.$tableKey.'.json', $current);

			dd($old,$current,$backwardsOpcodes, $change);

		}

		return dd($diffs);

	}


}