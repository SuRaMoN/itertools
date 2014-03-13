<?php

spl_autoload_register(function ($class) {
	$available_classes = array(
		'itertools\AbstractCsvIterator',
		'itertools\ArrayAccessIterator',
		'itertools\AutoTransactionBatchIterator',
		'itertools\CachingIterator',
		'itertools\CallbackFilterIterator',
		'itertools\CallbackIterator',
		'itertools\CallbackRecursiveIterator',
		'itertools\ChainIterator',
		'itertools\ChunkedIterator',
		'itertools\ChunkingIterator',
		'itertools\ComposingIterator',
		'itertools\CurrentCachedIterator',
		'itertools\DropWhileIterator',
		'itertools\FileCsvIterator',
		'itertools\FileLineIterator',
		'itertools\FixedLengthFormattedStringIterator',
		'itertools\ForkingIterator',
		'itertools\GroupByIterator',
		'itertools\HistoryIterator',
		'itertools\IterUtil',
		'itertools\LockingIterator',
		'itertools\LookAheadIterator',
		'itertools\MapIterator',
		'itertools\PairIterator',
		'itertools\PdoIterator',
		'itertools\Queue',
		'itertools\RangeIterator',
		'itertools\ReferencingIterator',
		'itertools\RepeatIterator',
		'itertools\SliceIterator',
		'itertools\StopwatchIterator',
		'itertools\StringCsvIterator',
		'itertools\SubstringLocation',
		'itertools\TakeWhileIterator',
		'itertools\TemplateToSubstringMapConverter',
		'itertools\UniqueIterator',
		'itertools\ZipIterator',
	);
	if(in_array($class, $available_classes)) {
		require(__DIR__ . '/../' . strtr($class, '\\', '/') . '.php');
	}
});

