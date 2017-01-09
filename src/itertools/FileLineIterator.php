<?php

namespace itertools;

use EmptyIterator;
use Exception;
use InvalidArgumentException;


class FileLineIterator extends TakeWhileIterator
{
    protected $fileHandle;
    protected $closeFileHandleOnDestruct;
    protected $guzzleStream;

    public function __construct($file, $options = array())
    {
        $defaultOptions = array(
            'includeWhitespace' => false,
            'fromEncoding' => null,
        );

        $unknownOptions = array_diff(array_keys($options), array_keys($defaultOptions));
        if (count($unknownOptions) != 0) {
            throw new InvalidArgumentException('Unknown options specified: ' . implode(', ', $unknownOptions));
        }

        $options = (object) array_merge($defaultOptions, $options);

        if ($file instanceof \Guzzle\Stream\StreamInterface) {
            $this->guzzleStream = $file;
            $file = $file->getStream();
        }

        if (is_resource($file)) {
            $this->fileHandle = $file;
            $this->closeFileHandleOnDestruct = false;
            if(null !== $options->fromEncoding) {
                throw new Exception('Source encoding can only be specified if constructed with file path');
            }
        } else if (is_string($file)) {
            $this->fileHandle = @fopen($file, 'r');
            if ($this->fileHandle === false) {
                throw new InvalidArgumentException("Could not open file with path: '$file'");
            }
            if (null !== $options->fromEncoding) {
                stream_filter_append($this->fileHandle, 'convert.iconv.' . $options->fromEncoding . '/UTF-8');
            }
            $this->closeFileHandleOnDestruct = true;

        } else {
            throw new InvalidArgumentException('You must provide either a stream or filename to the file line iterator, you provided a ' . gettype($file));
        }

        $fileHandle = $this->fileHandle;
        $lineIterator = new CallbackIterator(function () use ($fileHandle, $options) {
            $line = fgets($fileHandle);
            if ($line !== false && !$options->includeWhitespace) {
                $line = rtrim($line, "\r\n");
            }
            return $line;
        });
        parent::__construct($lineIterator, function ($line) { return $line !== false; });
    }

    public function __destruct()
    {
        if ($this->fileHandle !== null && $this->closeFileHandleOnDestruct) {
            fclose($this->fileHandle);
            $this->fileHandle = null;
        }
    }
}
