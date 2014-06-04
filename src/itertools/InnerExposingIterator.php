<?php

namespace itertools;

use IteratorIterator;
use OuterIterator;


class InnerExposingIterator extends IteratorIterator
{
	public function __construct($innerIterator)
	{
		parent::__construct(IterUtil::asTraversable($innerIterator));
	}

	public function __call($name, $arguments)
	{
		$iterator = $this;
		while($iterator instanceof OuterIterator) {
			$iterator = $iterator->getInnerIterator();
			if(method_exists($iterator, $name)) {
				return call_user_func_array(array($iterator, $name), $arguments);
			}
		}
	}
}
 
