<?php

spl_autoload_register(function ($class) {
	$available_classes = array(
		'itertools\IterUtil',
		'itertools\ChainIterator',
		'itertools\ChunkedIterator',
		'itertools\ChunkingIterator',
		'itertools\TakeWhileIterator',
		'itertools\CallbackIterator',
		'itertools\RepeatIterator',
		'itertools\MapIterator',
		'itertools\ForkingIterator',
		'itertools\PdoIterator',
		'itertools\CurrentCachedIterator',
		'itertools\AbstractCsvIterator',
		'itertools\FileCsvIterator',
		'itertools\StringCsvIterator',
		'itertools\CachingIterator',
		'itertools\CallbackFilterIterator',
		'itertools\CallbackRecursiveIterator',
		'itertools\FileLineIterator',
		'itertools\HistoryIterator',
		'itertools\UniqueIterator',
	);
	if(in_array($class, $available_classes)) {
		require(__DIR__ . '/../' . strtr($class, '\\', '/') . '.php');
	}
});

