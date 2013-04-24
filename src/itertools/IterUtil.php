<?php

namespace itertools;

use Traversable;
use IteratorAggregate;
use IteratorIterator;
use ArrayIterator;
use Iterator;
use Exception;


class IterUtil
{
	public static function traversableToIterator(Traversable $traversable)
	{
		if($traversable instanceof Iterator) {
			return $traversable;
		} else if($traversable instanceof IteratorAggregate) {
			return $traversable->getIterator();
		} else {
			return new IteratorIterator($traversable);
		}
	}

	public static function asIterator($iterable)
	{
		if(is_array($iterable)) {
			return new ArrayIterator($iterable);
		} else {
			return self::traversableToIterator($iterable);
		}
	}

	public static function getCurrentAndAdvance(&$iterable, $options = array())
	{
		if(is_array($iterable)) {
			$current = each($iterable);
		} else if($iterable instanceof Iterator) {
			if(!$iterable->valid()) {
				$current = false;
			} else {
				$current = array('value' => $iterable->current());
				$iterable->next();
			}
		}
		if($current === false) {
			if(array_key_exists('default', $options)) {
				return $options['default'];
			} else {
				throw new Exception('Error while advancing iterable');
			}
		}
		return $current['value'];
	}
}

