<?php namespace Fuzzybaird\Databasesync\Tests;
use Fuzzybaird\Databasesync\Classes\ColumnsAndTables;
use TestCase;

class ColumnsAndTablesTest extends TestCase
{
    public function testSelectTablesWithResponse()
    {
        $tables = new ColumnsAndTables;
        $all = $tables->selectTables(['backend_user_groups','backend_user_throttle']);

        $this->assertCount(2, $all);
    }
}