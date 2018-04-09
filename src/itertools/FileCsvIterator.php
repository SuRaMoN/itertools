<?php

namespace itertools;

use Exception;
use InvalidArgumentException;
use SplFileInfo;

class FileCsvIterator extends AbstractCsvIterator
{
    protected $fileHandle;
    protected $closeFileHandleOnDestruct;
    protected $guzzleStream;

    public function __construct($file, array $options = array())
    {
        $defaultOptions = array(
            'length' => 0,
            'fromEncoding' => null,
        );
        $this->options = array_merge($defaultOptions, $options);

        if ($file instanceof SplFileInfo) {
            $file = $file->getPathname();
        }

        if ($file instanceof \GuzzleHttp\Stream\StreamInterface) {
            $this->guzzleStream = $file;
            $file = (clone $file)->detach();
        }

        if ($file instanceof \Guzzle\Stream\StreamInterface) {
            $this->guzzleStream = $file;
            $file = $file->getStream();
        }

        if (is_resource($file)) {
            $this->fileHandle = $file;
            $this->closeFileHandleOnDestruct = false;
            if (null !== $this->options['fromEncoding']) {
                throw new Exception('Source encoding can only be specified if constructed with file path');
            }
        } elseif (is_string($file)) {
            $this->fileHandle = @fopen($file, 'r');
            if ($this->fileHandle === false) {
                throw new InvalidArgumentException("Could not open csv file with path: '$file'");
            }
            if (null !== $this->options['fromEncoding']) {
                stream_filter_append($this->fileHandle, 'convert.iconv.' . $this->options['fromEncoding'] . '/UTF-8');
            }
            $this->closeFileHandleOnDestruct = true;
        } else {
            throw new InvalidArgumentException('You must provide either a stream or filename to the csv iterator, you provided a ' . gettype($file));
        }

        parent::__construct(array_diff_key($options, $defaultOptions));
    }

    public function retrieveNextCsvRow()
    {
        return fgetcsv($this->fileHandle, $this->options['length'], $this->options['delimiter'], $this->options['enclosure'], $this->options['escape']);
    }

    public function __destruct()
    {
        if (null !== $this->fileHandle && $this->closeFileHandleOnDestruct) {
            fclose($this->fileHandle);
            $this->fileHandle = null;
        }
    }
}

