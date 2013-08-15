<?php

namespace itertools;

use ArrayIterator;
use Exception;
use InvalidArgumentException;
use Iterator;
use IteratorAggregate;
use IteratorIterator;
use Traversable;


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

	public static function asTraversable($iterable)
	{
		if(is_array($iterable)) {
			return new ArrayIterator($iterable);
		}
		if(!($iterable instanceof Traversable)) {
			throw new InvalidArgumentException("Can't create a traversable out of: " . get_class($iterable));
		}
		return $iterable;
	}

	public static function asIterator($iterable)
	{
		if(is_array($iterable)) {
			return new ArrayIterator($iterable);
		} else {
			return self::traversableToIterator($iterable);
		}
	}

	public static function isCollection($value)
	{
		switch(true) {
			case is_array($value):
			case $value instanceof Traversable:
				return true;
			default:
				return false;
		}
	}

	public static function assertIsCollection($value)
	{
		if(!self::isCollection($value)) {
			throw new Exception('The provided argument is not a collection: ' . gettype($value));
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

