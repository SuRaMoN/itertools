<?php

require_once(__DIR__ . '/../src/itertools/autoload.php');

spl_autoload_register(function ($class) {
	$availableClasses = array(
		'itertools\MockPDO',
	);
	if(in_array($class, $availableClasses)) {
		require(__DIR__ . '/' . strtr($class, '\\', '/') . '.php');
	}
});


