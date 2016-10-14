<?php

namespace itertools;

use Exception;
use IteratorIterator;
use itertools\IterUtil;
use PDO;

/**
 * When adding this itertator to a for loop you can automatically
 * batch updates in a transaction to speed up inserts or updates.
 *
 * Example:
 * foreach (new TimedAutoTransactionBatchIterator($entities, $pdo) as $entity) {
 *   $this->pdo->exec('UPDATE entity SET counter = counter + 1 WHERE id = ' . (int) $entity->getId());
 *   // Batching updates in a transaction can be have a huge performance improvement
 *   // By default the commit happens every second. Be aware, long transactions can cause dead locks
 * }
 */
class TimedAutoTransactionBatchIterator extends IteratorIterator
{
    const START_TRANSACTION = -1;

    protected $timeout;
    protected $pdo;
    protected $nextCommitTime = self::START_TRANSACTION;
    protected $inTransaction = false;

    public function __construct($iterator, PDO $pdo, $timeout = 1.0 /** s */)
    {
        parent::__construct(IterUtil::asTraversable($iterator));
        $this->timeout = $timeout;
        $this->pdo = $pdo;
    }

    public function valid()
    {
        $valid = parent::valid();
        if (! $valid) {
            $this->commitIfInTransaction();
            return false;
        }
        if (microtime(true) >= $this->nextCommitTime) {
            $this->nextCommitTime = self::START_TRANSACTION;
            $this->commitIfInTransaction();
        }
        if (self::START_TRANSACTION == $this->nextCommitTime) {
            $this->pdo->beginTransaction();
            $this->inTransaction = true;
            $this->nextCommitTime = microtime(true) + $this->timeout;
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

    public function __destruct()
    {
        if (! $this->inTransaction) {
            return;
        }
        // this can only be destructed in transaction if exception occured
        $this->pdo->rollBack();
    }
}

