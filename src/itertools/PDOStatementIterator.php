<?php

namespace itertools;

use Iterator;
use PDO;
use PDOStatement;


class PDOStatementIterator implements Iterator
{
    protected $stmtFactory;
	protected $fetchStyle;
	protected $currentValue;
	protected $currentKey;

    public function __construct($stmt, $fetchStyle = PDO::FETCH_BOTH)
    {
		if(is_callable($stmt)) {
			$this->stmtFactory = $stmt;
		} else {
			$this->stmtFactory = function() use ($stmt) { return $stmt; };
		}
		$this->fetchStyle = $fetchStyle;
        $this->stmt = $stmt;
    }

    public function rewind()
    {
		$this->stmt = call_user_func($this->stmtFactory);
		$this->next();
		$this->currentKey = 0;
    }

    public function valid()
    {
        return false !== $this->currentValue;
    }

    public function current()
    {
        return $this->currentValue;
    }

    public function key()
    {
        return $this->currentKey;
    }

    public function next()
    {
		$this->currentKey += 1;
		$this->currentValue = $this->stmt->fetch($this->fetchStyle);
    }
}

