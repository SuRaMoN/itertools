<?php

namespace itertools;

use itertools\PDOStatementIterator;
use PDO;
use PHPUnit_Framework_TestCase;


class PDOStamentIteratorTest extends PHPUnit_Framework_TestCase
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
	public function testNormalStatementIteration()
	{
		$it = new PDOStatementIterator($this->pdo->query('select name from entries limit 0, 2'), PDO::FETCH_NUM);
		$this->assertEquals(array(array('0'), array('1')), iterator_to_array($it));
		$this->assertEquals(array(), iterator_to_array($it));
	}

	/** @test */
	public function testFactoryStatementIteration()
	{
		$pdo = $this->pdo;
		$factory = function () use ($pdo) { return $pdo->query('select name from entries limit 0, 2'); };
		$it = new PDOStatementIterator($factory, PDO::FETCH_NUM);
		$this->assertEquals(array(array('0'), array('1')), iterator_to_array($it));
		$this->assertEquals(array(array('0'), array('1')), iterator_to_array($it));
	}
}

