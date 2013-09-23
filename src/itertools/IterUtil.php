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
			throw new InvalidArgumentException('Can\'t create a traversable out of: ' . (is_object($iterable) ? get_class($iterable) : gettype($iterable)));
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

	public static function any($iterable, $callable = null)
	{
		if(null !== $callable && ! is_callable($callable)) {
			throw new InvalidArgumentException('No valid callable is supplied');
		}
		if(null === $callable) {
			return self::anyWithoutCallable($iterable);
		} else {
			return self::anyWithCallable($iterable, $callable);
		}
	}

	protected static function anyWithoutCallable($iterable)
	{
		foreach($iterable as $element) {
			if($element) {
				return true;
			}
		}
		return false;
	}

	protected static function anyWithCallable($iterable, $callable)
	{
		foreach($iterable as $element) {
			if(call_user_func($callable, $element)) {
				return true;
			}
		}
		return false;
	}

	public static function all($iterable, $callable = null)
	{
		if(null !== $callable && ! is_callable($callable)) {
			throw new InvalidArgumentException('No valid callable is supplied');
		}
		if(null === $callable) {
			return self::allWithoutCallable($iterable);
		} else {
			return self::allWithCallable($iterable, $callable);
		}
	}

	protected static function allWithoutCallable($iterable)
	{
		foreach($iterable as $element) {
			if(! $element) {
				return false;
			}
		}
		return true;
	}

	protected static function allWithCallable($iterable, $callable)
	{
		foreach($iterable as $element) {
			if(! call_user_func($callable, $element)) {
				return false;
			}
		}
		return true;
	}

	public static function recursive_iterator_to_array(Iterator $iterator, $useKeys = true)
	{
		$resultArray = array();
		$index = 0;
		foreach($iterator as $key => $element) {
			if(!$useKeys) {
				$key = $index;
			}
			if($element instanceof Iterator) {
				$resultArray[$key] = self::recursive_iterator_to_array($element, $useKeys);
			} else {
				$resultArray[$key] = $element;
			}
			$index += 1;
		}
		return $resultArray;
	}

	public static function assertIsCollection($value)
	{
		if(!self::isCollection($value)) {
			throw new Exception('The provided argument is not a collection: ' . (is_object($value) ? get_class($value) : gettype($value)));
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

