itertools
=========

A set of iterators for PHP based on pythons [itertools](https://docs.python.org/2/library/itertools.html).

[![Build Status](https://travis-ci.org/SuRaMoN/itertools.png?branch=master)](https://travis-ci.org/SuRaMoN/itertools) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/SuRaMoN/itertools/badges/quality-score.png?s=e5c12675df1cfe519f2e2a8f89197f33ceb8304c)](https://scrutinizer-ci.com/g/SuRaMoN/itertools/) [![Code Coverage](https://scrutinizer-ci.com/g/SuRaMoN/itertools/badges/coverage.png?s=58f4d2d1cea8f7a5c4e7625404af3844eb8f2ebb)](https://scrutinizer-ci.com/g/SuRaMoN/itertools/)

Some iterator examples
======================

```php
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
```

Install Guide
=============

```
composer require suramon/itertools
```

Manual Install
--------------
1. [Download itertools](https://github.com/SuRaMoN/itertools/archive/master.zip) directly from GitHub, or clone it with Git: `git clone https://github.com/SuRaMoN/relike.git`
2. Include the autoload header located in `src/autload.php` (eg: `require 'relike/src/autoload.php';`) or you can use [psr-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
3. Learn and use itertools by looking at the [examples](https://github.com/SuRaMoN/itertools), [tests](https://github.com/SuRaMoN/itertools/tree/master/tests) and [source code](https://github.com/SuRaMoN/itertools/tree/master/src/itertools)

Some iterators explained
========================

ChainIterator
-------------
Iterator equivalent of flattening a 2-dimensional array.

```php
$iterators = new ArrayIterator(array(new RangeIterator(1, 5), new RangeIterator(6, 10)));
$fattenedIterators = new ChainIterator($iterators); // will contain all numbers from 1 to 10
```

ChunkingIterator
----------------
Splits a iterator into smaller chunks. this can be used for batch processing.

```php
$iterator = new RangeIterator();
$batchsize = 100;
foreach(new ChunkingIterator($iterator, $batchsize) as $chunk) {
    $pdo->starttransaction();
    foreach($chunk as $element) {
        // process the iterator elements. using the transaction inside the chunkiterator makes sure the transaction stays small
    }
    $pdo->commit();
}
```

ForkingIterator
---------------
This linux-only iterator is designed to be iterated by a foreach loop and
forks a new process for each iteration.

```php
$elements = new RangeIterator(0, 10);
foreach(new ForkingIterator($elements) as $i) {
    var_dump($i, getmypid()); // wil spawn a new process to iterate each element
}
```

HistoryIterator
---------------
An iterator that keeps track of the elements it iterates. It differs from
the CachingIterator in the standard PHP library because this implementations
allows the history size to be specified.

```php
$range = new HistoryIterator(new ArrayIterator(range(1, 10)));
foreach($range as $i) {
    if($range->hasPrev()) {
        echo $i, $range->prev(), "\n";
    }
}
```

MapIterator
-----------
Iterator equivalent or [array_map](https://www.php.net/manual/en/function.array-map.php).

```php
$positiveNumbers = new RangeIterator(0, INF); // all numbers from 0 to infinity
$positiveSquareNumbers = new MapIterator($positiveNumbers, function($n) {return $n*$n;}); // all positive square numbers
```

SliceIterator
-------------
Iterator equivalent of [array_slice](https://www.php.net/manual/en/function.array-slice.php).

    $lines = new SliceIterator(new FileLineIterator('file.txt'), 0, 1000); // will iterate the first 1000 lines of the file


UniqueIterator
--------------
Iterator equivalent of [array_unique](https://www.php.net/manual/en/function.array-unique.php) but only works for sorted input.

```php
$uniqueEntries = new UniqueIterator(new ArrayIterator(array(1, 2, 2, 2, 3, 4, 2))); // will contain 1, 2, 3, 4, 2
```

RangeIterator
-------------
Iterator equivalent of [range](https://www.php.net/manual/en/function.range.php).

```php
$lines = new SliceIterator(new FileLineIterator('file.txt'), 0, 1000); // will iterate the first 1000 lines of the file
```

ZipIterator
-----------
Inspired by pythons [zip](https://docs.python.org/3.1/library/functions.html#zip) function. It can be constructed with an array of iterators and it iterates all of its arguments at the same index, returning during each iteration an array of the elements of each iterator on the same iteration positon

```php
$csv1 = new FileCsvIterator('file1.csv');
$csv2 = new FileCsvIterator('file2.csv');
foreach(new ZipIterator(array($csv1, $csv2)) as $combinedRows) {
    $row1 = $combinedRows[0]; // a row in file1.csv
    $row2 = $combinedRows[1]; // row in file2.csv on same position
}
```
