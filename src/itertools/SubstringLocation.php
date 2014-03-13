<?php

namespace itertools;


class SubstringLocation
{
	protected $offset;
	protected $length;

	public function __construct($offset, $length)
	{
		$this->offset = $offset;
		$this->length = $length;
	}

 	public function getOffset()
 	{
 		return $this->offset;
 	}

 	public function getLength()
 	{
 		return $this->length;
 	}
}
 
