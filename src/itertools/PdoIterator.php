<?php

namespace itertools;

use PDO;


class PdoIterator extends TakeWhileIterator
{
	public function __construct(PDO $pdo, $query) {
		$pdoStatement = $pdo->query($query);
		$it = new CallbackIterator(function() use ($pdoStatement) {
			return $pdoStatement->fetchObject();
		});
		parent::__construct($it, function($r) { return $r !== false; });
	}
}

