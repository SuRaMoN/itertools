<?php

namespace itertools;

use PDO;
use PHPUnit_Framework_TestCase;
use stdClass;


class PdoIteratorTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testMainFunctionality()
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

		$it = new PdoIterator($pdo, 'SELECT * FROM entries');
		$rows = iterator_to_array($it);
		$this->assertEquals(10, count($rows));
		$this->assertTrue($rows[0] instanceof stdClass);
		foreach($rows as $i => $row) {
			$this->assertEquals($i, $row->name);
		}

		$it = new PdoIterator($pdo, 'SELECT * FROM entries WHERE name > :minName', array(':minName' => 5));
		$this->assertEquals(4, count(iterator_to_array($it)));
	}
}

