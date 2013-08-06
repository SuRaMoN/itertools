<?php

namespace itertools;

use PDO;


class PdoIterator extends TakeWhileIterator
{
	public function __construct(PDO $pdo, $query, $params = array())
	{
		if(count($params) != 0) {
			$pdoStatement = $pdo->prepare($query);
			$pdoStatement->execute($params);
		} else {
			$pdoStatement = $pdo->query($query);
		}
		$it = new CallbackIterator(function() use ($pdoStatement) {
			return $pdoStatement->fetchObject();
		});
		parent::__construct($it, function($r) { return $r !== false; });
	}
}

