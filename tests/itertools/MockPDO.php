<?php

namespace itertools;

use PDO;


class MockPDO extends PDO
{
	protected $callBacks;

	public function __construct(array $callBacks = array())
	{
		$this->callBacks = $callBacks;
	}

	protected function triggerCallback($methodName, array $arguments = array())
	{
		if(array_key_exists($methodName, $this->callBacks)) {
			call_user_func_array($this->callBacks[$methodName], $arguments);
		}
	}

	public function rollBack()
	{
		$this->triggerCallback(__FUNCTION__);
	}

	public function beginTransaction()
	{
		$this->triggerCallback(__FUNCTION__);
	}

	public function commit()
	{
		$this->triggerCallback(__FUNCTION__);
	}
}
 
