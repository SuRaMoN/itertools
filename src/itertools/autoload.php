<?php

spl_autoload_register(function ($class) {
	$available_classes = array(
		'itertools\IterUtil',
		'itertools\ChainIterator',
		'itertools\ChunkedIterator',
		'itertools\TakeWhileIterator',
		'itertools\CallbackIterator',
		'itertools\RepeatIterator',
		'itertools\MapIterator',
		'itertools\ForkingIterator',
	);
	if(in_array($class, $available_classes)) {
		require(__DIR__ . '/../' . strtr($class, '\\', '/') . '.php');
	}
});

