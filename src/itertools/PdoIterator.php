<?php

namespace itertools;

use Exception;
use PDO;


class PdoIterator extends TakeWhileIterator
{
	public function __construct(PDO $pdo, $query, $params = array(), $fetchMode = PDO::FETCH_OBJ)
	{
		$pdoStatement = null;
		$it = new CallbackIterator(function() use (& $pdoStatement, $pdo, $query, $params, $fetchMode) {
			if(null === $pdoStatement) {
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
			}
			return $pdoStatement->fetch();
		});
		parent::__construct($it, function($r) { return $r !== false; });
	}
}

