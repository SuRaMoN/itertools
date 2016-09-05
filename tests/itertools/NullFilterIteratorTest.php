<?php

namespace itertools;

use PHPUnit_Framework_TestCase;
use stdClass;

class NullFilterIteratorTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function testNullFilter()
    {
        $object = new stdClass();
        $input = array(0, 'null', null, false, $object);
        $expectedResult = array(0, 'null', false, $object);
        $this->assertEquals($expectedResult, iterator_to_array(new NullFilterIterator($input), false));
    }
}
