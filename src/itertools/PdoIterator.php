<?php

namespace itertools;

use Exception;
use PDO;


class PdoIterator extends TakeWhileIterator
{
	public function __construct(PDO $pdo, $query, $params = array(), $fetchMode = PDO::FETCH_OBJ)
	{
		if(count($params) != 0) {
			$pdoStatement = $pdo->prepare($query);
			if(false === $pdoStatement) {
				throw new Exception('Invalid query');
			}
			$pdoStatement->execute($params);
		} else {
			$pdoStatement = $pdo->query($query);
		}
		if(false === $pdoStatement) {
			throw new Exception('Invalid query');
		}
		$pdoStatement->setFetchMode($fetchMode);
		$it = new CallbackIterator(function() use ($pdoStatement) {
			return $pdoStatement->fetch();
		});
		parent::__construct($it, function($r) { return $r !== false; });
	}
}

