<?php

namespace itertools;

use EmptyIterator;
use Exception;
use InvalidArgumentException;

abstract class AbstractCsvIterator extends TakeWhileIterator
{
    protected $options = array();

    public function __construct(array $options = array())
    {
        $defaultOptions = array(
            'delimiter' => ',',
            'enclosure' => '"',
            'escape' => '\\',
            'hasHeader' => true,
            'header' => null,
            'ignoreMissingRows' => false, // Deprecated: use ignoreMissingColumns
            'ignoreMissingColumns' => false, // columns*
            'combineFirstNRowsAsHeader' => 1,
            'skipFirstNRows' => 0
        );

        $unknownOptions = array_diff(array_keys($options), array_keys($defaultOptions));
        if(count($unknownOptions) != 0) {
            throw new InvalidArgumentException('Unknown options specified: ' . implode(', ', $unknownOptions));
        }
        if(array_key_exists('header', $options) && null !== $options['header'] && ! array_key_exists('hasHeader', $options)) {
            $options['hasHeader'] = false;
        }
        if(array_key_exists('ignoreMissingRows', $options)) {
            $options['ignoreMissingColumns'] = $options['ignoreMissingRows'];
        }

        $this->options = array_merge($this->options, $defaultOptions, $options);

        if($this->options['hasHeader'] || null !== $this->options['header']) {
            if(null === $this->options['header']) {
                $it = $this->getLineIteratorWithHeaderInFirstLines();
            } else {
                $i = 0;
                while($this->options['skipFirstNRows'] > $i) {
                    $i++;
                    $this->retrieveNextCsvRow();
                }

                $it = $this->getLineIteratorWithHeader($this->options['header']);
            }
        } else {
            $it = $this->getLineIteratorWithoutHeader();
        }
        parent::__construct($it, function($r) { return $r !== false; });
    }

    abstract public function retrieveNextCsvRow();

    protected function getLineIteratorWithoutHeader()
    {
        return new SliceIterator(new CallbackIterator(array($this, 'retrieveNextCsvRow')), $this->options['skipFirstNRows']);
    }

    protected function getLineIteratorWithHeader($header)
    {
        if(false === $header || null === $header) {
            return new EmptyIterator();
        }
        $nextRowRetriever = array($this, 'retrieveNextCsvRow');
        $options = $this->options;

        if ($options['hasHeader'] && null !== $options['header']) {
            $this->retrieveNextCsvRow();
        }
        return new CallbackIterator(function() use ($nextRowRetriever, $header, $options) {
            $row = call_user_func($nextRowRetriever);
            if(false === $row || array(null) === $row) {
                return false;
            }
            if(count($header) == count($row)) {
                return array_combine($header, $row);
            }
            if(! $options['ignoreMissingColumns']) {
                throw new InvalidCsvException('Your headers and columns do not match');
            }
            if(count($header) < count($row)) {
                $row = array_slice($row, 0, count($header));
            } else {
                $row = array_merge($row, array_fill(0, count($header) - count($row), null));
            }
            return array_combine($header, $row);
        });
    }

    protected function getLineIteratorWithHeaderInFirstLines()
    {
        $headers = array_map(array($this, 'retrieveNextCsvRow'), range(1, $this->options['skipFirstNRows'] + $this->options['combineFirstNRowsAsHeader']));
        $combinedHeader = null;
        $i = 0;
        foreach($headers as $header) {
            if($this->options['skipFirstNRows'] > $i) {
                $i++;
                continue;
            }

            if(false === $header || null === $header) {
                return new EmptyIterator();
            }
            if(null === $combinedHeader) {
                $combinedHeader = array_fill_keys(array_keys($header), '');
            }
            $previousNonEmptyColumnTitle = '';
            foreach($header as $i => $columnTitle) {
                if(! array_key_exists($i, $header)) {
                    if(! $this->options['ignoreMissingColumns']) {
                        throw new InvalidCsvException('You provided a csv with missing columns');
                    } else {
                        continue;
                    }
                }
                $combinedHeader[$i] .=
                    ('' == trim($combinedHeader[$i]) || '' == trim($columnTitle) ? '' : ' ') .
                    ('' == trim($columnTitle) ? $previousNonEmptyColumnTitle : $columnTitle);
                if('' != trim($columnTitle)) {
                    $previousNonEmptyColumnTitle = $columnTitle;
                }
            }
        }
        if(null === $combinedHeader) {
            throw new Exception('Unable to get header');
        }
        // make columns unique (double columns become empty)
        $previousColumns = array();
        foreach($combinedHeader as & $name) {
            if(array_key_exists($name, $previousColumns)) {
                $name = '';
            }
            $previousColumns[$name] = '';
        }
        return $this->getLineIteratorWithHeader($combinedHeader);
    }
}

