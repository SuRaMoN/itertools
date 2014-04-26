<?php

namespace itertools;

use InvalidArgumentException;


class ProcessCsvIterator extends AbstractCsvIterator
{
	protected $cmd;
	protected $cwd;
	protected $env;
	protected $otherOptions;

	protected $processHandle;
	protected $stdOutHandle;
	protected $processStarted = false;

	public function __construct($cmd, array $options = array(), $cwd = null, $env = null, array $otherOptions = null)
	{
		$defaultOptions = array(
			'length' => 0,
		);
		$this->options = array_merge($defaultOptions, $options);
		parent::__construct(array_diff_key($options, $defaultOptions));
		$this->cmd = $cmd;
		$this->cwd = $cwd;
		$this->env = $env;
		$this->otherOptions = $otherOptions;
	}

	protected function ensureProcessStarted()
	{
		if($this->processStarted) {
			return;
		}

		$descriptorSpec = array(
			0 => array('pipe', 'r'),
			1 => array('pipe', 'w'),
		);

		$this->processHandle = proc_open($this->cmd, $descriptorSpec, $pipes, $this->cwd, $this->env, $this->otherOptions);

		if(! is_resource($this->processHandle)) {
			throw new InvalidArgumentException("Unable to open process: $cmd");
		}

		fclose($pipes[0]);
		$this->stdOutHandle = $pipes[1];

		$this->processStarted = true;
	}

	public function retrieveNextCsvRow()
	{
		$this->ensureProcessStarted();
		return fgetcsv($this->stdOutHandle, $this->options['length'], $this->options['delimiter'], $this->options['enclosure'], $this->options['escape']);
	}

	public function __destruct()
	{
		proc_close($this->processHandle);
	}
}
 
