<?php

namespace itertools;

use PHPUnit_Framework_TestCase;
use PDO;

class PdoIteratorTest extends PHPUnit_Framework_TestCase {

	/** @test */
	public function testMainFunctionality() {
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

		$this->assertEquals(10, count(iterator_to_array($it)));
	}
}

