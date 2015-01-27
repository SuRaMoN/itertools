<?php

namespace itertools;

use Exception;
use IteratorIterator;
use itertools\IterUtil;
use PDO;


class AutoTransactionBatchIterator extends IteratorIterator
{
	const START_TRANSACTION = -1;

	protected $batchSize;
	protected $pdo;
	protected $currentStep = self::START_TRANSACTION;
	protected $inTransaction = false;

	public function __construct($iterator, PDO $pdo, $batchSize = 100)
	{
		$this->batchSize = $batchSize;
		$this->pdo = $pdo;
		parent::__construct(IterUtil::asTraversable($iterator));
	}

	public function valid()
	{
		$valid = parent::valid();
		if(! $valid) {
			$this->commitIfInTransaction();
			return false;
		}
		if($this->currentStep >= $this->batchSize) {
			$this->currentStep = self::START_TRANSACTION;
			$this->commitIfInTransaction();
		}
		if(self::START_TRANSACTION == $this->currentStep) {
			$this->pdo->beginTransaction();
			$this->inTransaction = true;
			$this->currentStep = 0;
		}
		return true;
	}

	public function commitIfInTransaction()
	{
		if(! $this->inTransaction) {
			return;
		}
		try {
			$this->pdo->commit();
			$this->inTransaction = false;
		} catch(Exception $e) {
			$this->pdo->rollBack();
			$this->inTransaction = false;
			throw $e;
		}
	}

	public function next()
	{
		$this->currentStep += 1;
		parent::next();
	}

	public function __destruct()
	{
		if(! $this->inTransaction) {
			return;
		}
		// this can only be destructed in transaction if exception occured
		$this->pdo->rollBack();
	}
}

