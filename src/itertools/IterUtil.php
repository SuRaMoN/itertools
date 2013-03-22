<?php

namespace itertools;

use Traversable;
use IteratorAggregate;
use IteratorIterator;
use ArrayIterator;
use Iterator;


class IterUtil {

	public static function traversableToIterator(Traversable $traversable) {
		if($traversable instanceof Iterator) {
			return $traversable;
		} else if($traversable instanceof IteratorAggregate) {
			return $traversable->getIterator();
		} else {
			return new IteratorIterator($traversable);
		}
	}

	public static function asIterator($iterable) {
		if(is_array($iterable)) {
			return new ArrayIterator($iterable);
		} else {
			return self::traversableToIterator($iterable);
		}
	}
}
 
