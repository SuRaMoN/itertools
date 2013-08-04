itertools
=========

A set of iterators based on pythons [itertools](http://docs.python.org/2/library/itertools.html).

[![Build Status](https://travis-ci.org/SuRaMoN/itertools.png?branch=master)](https://travis-ci.org/SuRaMoN/itertools)

Some iterator examples
======================

    # iterate the lines of a csv file
    $lines = new FileLineIterator('file.csv');
    # filter all non unique lines
    $uniqueLines = new UniqueIterator($lines);
    # convert unique csv string lines to array
    $rows = new StringCsvIterator($uniqueLines);
    # extract column 1 from the csv file
    $column1 = new MapIterator($rows, function($row) { return $row['column1']; });
    # output all rows in parallel
    foreach(new ForkingIterator($column1) as $row) {
        echo $row
    }


Install Guide
============

1. Download itertools (you can [download](https://github.com/SuRaMoN/itertools/archive/master.zip) directly from github, or clone it with git: `git clone https://github.com/SuRaMoN/relike.git`, or use [composer](http://getcomposer.org/))
2. include the autoload header located in src/autload.php (eg: `require('relike/src/autload.php');`) or you can use [psr-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
3. Learn and use itertools by looking at the [examples](https://github.com/SuRaMoN/itertools), [tests](https://github.com/SuRaMoN/itertools/tree/master/tests) and [source code](https://github.com/SuRaMoN/itertools/tree/master/src/itertools)
