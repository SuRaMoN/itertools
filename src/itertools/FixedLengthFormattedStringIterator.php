<?php

namespace itertools;

use InvalidArgumentException;
use Exception;


class FixedLengthFormattedStringIterator extends MapIterator
{
	protected $substringMap;
	protected $trim = null;

	public function __construct($inputIterator, array $substringMap, array $options = array())
	{
		$this->substringMap = $substringMap;

		if(array_key_exists('trim', $options)) {
			$this->trim = $options['trim'];
			unset($options['trim']);
		}

		if(count($options) > 0) {
			throw new InvalidArgumentException('Unknow options provided: ' . implode(',', array_keys($options)));
		}

		parent::__construct($inputIterator, array($this, 'parseString'));
	}

	static function newFromTemplate($inputIterator, $template, array $nameMap = array(), array $options = array())
	{
		$converter = new TemplateToSubstringMapConverter();
		return new self($inputIterator, $converter->convert($template, $nameMap), $options);
	}

	public function parseString($inputString)
	{
		$fields = array();
		foreach($this->substringMap as $name => $location) {
			$fields[$name] = substr($inputString, $location->getOffset(), $location->getLength());
			if(null !== $this->trim) {
				$fields[$name] = trim($fields[$name], $this->trim);
			}
		}
		return $fields;
	}
}
