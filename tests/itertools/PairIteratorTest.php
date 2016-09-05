<?php

namespace itertools;

use PHPUnit_Framework_TestCase;

class PairIteratorTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function testPairIterator()
    {
        $count = 0;
        foreach (new PairIterator(range(0, 10)) as $pair) {
            $this->assertEquals(array($count, $count + 1), $pair);
            $count += 1;
        }
        $this->assertEquals(10, $count);
    }
}
