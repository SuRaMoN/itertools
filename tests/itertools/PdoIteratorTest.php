<?php

namespace itertools;

use PDO;
use PHPUnit_Framework_TestCase;
use stdClass;


class PdoIteratorTest extends PHPUnit_Framework_TestCase
{
	protected $pdo;

	public function setup()
	{
		$pdo = new PDO('sqlite::memory:');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$pdo->exec('
			CREATE TABLE "entries"(
			  "id" INTEGER PRIMARY KEY NOT NULL,
			  "name" int
			);
		');
		foreach(range(0, 9) as $i) {
			$pdo->exec('INSERT INTO entries ("name") VALUES (' . $i . ')');
		}
		$this->pdo = $pdo;
	}

	public function tearDown()
	{
		$this->pdo = null;
	}

	/** @test */
	public function testBasicFunctionality()
	{
		$it = new PdoIterator($this->pdo, 'SELECT * FROM entries');
		$rows = iterator_to_array($it);
		$this->assertEquals(10, count($rows));
		$this->assertTrue($rows[0] instanceof stdClass);
		foreach($rows as $i => $row) {
			$this->assertEquals($i, $row->name);
		}

		$it = new PdoIterator($this->pdo, 'SELECT * FROM entries WHERE name > :minName', array(':minName' => 5));
		$this->assertEquals(4, count(iterator_to_array($it)));
	}

	/** @test */
	public function testFetchAssoc()
	{
		$it = new PdoIterator($this->pdo, 'SELECT * FROM entries', array(), PDO::FETCH_ASSOC);
		$rows = iterator_to_array($it);
		$this->assertEquals(10, count($rows));
		foreach($rows as $i => $row) {
			$this->assertEquals($i, $row['name']);
		}
	}
}

