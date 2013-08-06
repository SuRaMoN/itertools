itertools
=========

A set of iterators for PHP based on pythons [itertools](http://docs.python.org/2/library/itertools.html).

[![Build Status](https://travis-ci.org/SuRaMoN/itertools.png?branch=master)](https://travis-ci.org/SuRaMoN/itertools)

Some iterator examples
======================

    // iterate the lines of a csv file
    $lines = new FileLineIterator('file.csv');
    // filter all non unique lines
    $uniqueLines = new UniqueIterator($lines);
    // convert unique csv string lines to array
    $rows = new StringCsvIterator($uniqueLines);
    // extract column 1 from the csv file
    $column1 = new MapIterator($rows, function($row) { return $row['column1']; });
    // output all rows in parallel
    foreach(new ForkingIterator($column1) as $row) {
        echo $row;
    }


Install Guide
=============

1. Download itertools (you can [download](https://github.com/SuRaMoN/itertools/archive/master.zip) directly from github, or clone it with git: `git clone https://github.com/SuRaMoN/relike.git`, or use [composer](http://getcomposer.org/))
2. include the autoload header located in src/autload.php (eg: `require('relike/src/autoload.php');`) or you can use [psr-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
3. Learn and use itertools by looking at the [examples](https://github.com/SuRaMoN/itertools), [tests](https://github.com/SuRaMoN/itertools/tree/master/tests) and [source code](https://github.com/SuRaMoN/itertools/tree/master/src/itertools)

Some iterators explained
========================

ZipIterator
-----------
Inspired by pythons [zip](http://docs.python.org/3.1/library/functions.html#zip) function. It can be constructed with an array of iterators and it iterates all of its arguments at the same same, returning an array of the elements of each iterator on the same iteration positon

    $csv1 = new FileCsvIterator('file1.csv');
    $csv2 = new FileCsvIterator('file2.csv');
    foreach(new ZipIterator(array($csv1, $csv2)) as $combinedRows) {
        $row1 = $combinedRows[0]; // a row in file1.csv
        $row2 = $combinedRows[1]; // row in file2.csv on same position
    }

MapIterator
-----------
Iterator equivalent or [array_map](http://be1.php.net/manual/en/function.array-map.php).

    $positiveNumbers = new RangeIterator(0, INF); // all numbers from 0 to infinity
    $positiveSquareNumbers = new MapIterator($positiveNumbers, function($n) {return $n*$n;}); // all positive square numbers

ChainIterator
-------------
Iterator equivalent of flattening a 2-dimensional array.

    $iterators = new ArrayIterator(array(new RangeIterator(1, 5), new RangeIterator(6, 10)));
    $fattenedIterators = new ChainIterator($iterators); // will contain all numbers from 1 to 10

ChunkingIterator
----------------
Splits a iterator into smaller chunks. This can be used for batch processing.

    $iterator = new RangeIterator();
    $batchSize = 100;
    foreach(new Hunkingiterator($iterator, $batchSize) as $chunk) {
        $pdo->startTransaction();
        foreach($chunk as $element) {
            // process the iterator elements. Using the transaction inside the CHunkItertor makes sure the transaction stays small
        }
        $pdo->commit();
    }