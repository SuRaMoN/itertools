<?php

namespace itertools;


class PairIterator extends LookAheadIterator
{
	public function current()
	{
		return array(parent::current(), parent::getNext());
	}

	public function valid()
	{
		return parent::hasNext();
	}
}
 
