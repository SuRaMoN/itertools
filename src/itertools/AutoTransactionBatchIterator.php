<?php

namespace itertools;

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
			if($this->inTransaction) {
				$this->inTransaction = false;
				$this->pdo->commit();
			}
			return false;
		}
		if($this->currentStep >= $this->batchSize) {
			$this->inTransaction = false;
			$this->currentStep = self::START_TRANSACTION;
			$this->pdo->commit();
		}
		if(self::START_TRANSACTION == $this->currentStep) {
			$this->pdo->beginTransaction();
			$this->inTransaction = true;
			$this->currentStep = 0;
		}
		return true;
	}

	public function next()
	{
		$this->currentStep += 1;
		parent::next();
	}
}

