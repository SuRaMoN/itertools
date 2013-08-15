<?php

namespace itertools;

use PHPUnit_Framework_TestCase;


class QueueTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function testQueuePushPop()
	{
		$queue = new Queue();

		$queue->push(1);
		$this->assertEquals(1, $queue->getMemoryUsageDataStructure(), 'Should have exponential memory growth');

		$queue->push(2);
		$this->assertEquals(2, $queue->getMemoryUsageDataStructure(), 'Should have exponential memory growth');

		$queue->push(3);
		$this->assertEquals(4, $queue->getMemoryUsageDataStructure(), 'Should have exponential memory growth');

		$this->assertEquals(3, $queue->count());

		$this->assertEquals(3, $queue->pop());
		$this->assertEquals(4, $queue->getMemoryUsageDataStructure(), 'Memory usage should never shrink');

		$this->assertEquals(2, $queue->count());

		$this->assertEquals(2, $queue->pop());
		$this->assertEquals(1, $queue->pop());

		$this->assertEquals(0, $queue->count());
	}

	/** @test */
	public function testQueueShiftUnshift()
	{
		$queue = new Queue();

		$queue->unshift(1);
		$this->assertEquals(1, $queue->offsetGet(0));
		$this->assertEquals(1, $queue->getMemoryUsageDataStructure(), 'Should have exponential memory growth');

		$queue->unshift(2);
		$this->assertEquals(2, $queue->offsetGet(0));
		$this->assertEquals(2, $queue->getMemoryUsageDataStructure(), 'Should have exponential memory growth');

		$queue->unshift(3);
		$this->assertEquals(3, $queue->offsetGet(0));
		$this->assertEquals(4, $queue->getMemoryUsageDataStructure(), 'Should have exponential memory growth');

		$this->assertEquals(3, $queue->count());


		$this->assertEquals(3, $queue->shift());
		$this->assertEquals(4, $queue->getMemoryUsageDataStructure(), 'Memory usage should never shrink');

		$this->assertEquals(2, $queue->count());

		$this->assertEquals(2, $queue->shift());
		$this->assertEquals(1, $queue->shift());

		$this->assertEquals(0, $queue->count());
	}

	/** @test */
	public function testQueuePushAndUnshift()
	{
		$queue = new Queue();
		$queue->push(4);
		$queue->unshift(3);
		$queue->push(5);
		$queue->unshift(2);
		$queue->push(6);
		$queue->unshift(1);
		$this->assertEquals(array(1, 2, 3, 4, 5, 6), $queue->asArray());
	}

	/** @test */
	public function testQueuePopShiftPushAndUnshift()
	{
		$queue = new Queue();
		$queue->push(4);
		$queue->unshift('a');
		$queue->push(5);
		$queue->shift();
		$queue->push('b');
		$queue->unshift(3);
		$queue->pop();
		$queue->unshift(2);
		$queue->push(6);
		$queue->unshift(1);
		$this->assertEquals(array(1, 2, 3, 4, 5, 6), $queue->asArray());
	}

	/** @test */
	public function testQueuePushAll()
	{
		$queue = new Queue();
		$queue->pushAll(array(1, 2, 3));
		$this->assertEquals(array(1, 2, 3), $queue->asArray());
	}

	/** @test */
	public function testUnshiftAll()
	{
		$queue = new Queue();
		$queue->unshiftAll(array(1, 2, 3));
		$this->assertEquals(array(3, 2, 1), $queue->asArray());
	}

	/** @test */
	public function testQueueBottom()
	{
		$queue = new Queue(array(1, 2, 3));
		$this->assertEquals(1, $queue->bottom());
	}

	/** @test */
	public function testQueueTop()
	{
		$queue = new Queue(array(1, 2, 3));
		$this->assertEquals(3, $queue->top());
	}

	/** @test */
	public function testQueueIteration()
	{
		$queue = new Queue(array(1, 2, 3));
		$this->assertEquals(array(1, 2, 3), $queue->asArray());
	}

	/** @test */
	public function testQueueOffsetGet()
	{
		$queue = new Queue(array(-1, 0, 1, 2, 3));
		$queue->shift();
		$queue->push(4);
		$this->assertEquals(1, $queue->get(1));
		$this->assertEquals(2, $queue->offsetGet(2));
	}

	/** @test */
	public function testQueueOffsetSet()
	{
		$queue = new Queue(array(-1, 0, 1, 2, 3));
		$queue->shift();
		$queue->push(4);
		$queue->set(0, 'a');
		$queue->offsetSet(1, 'b');
		$queue->offsetSet(5, 'f');
		$this->assertEquals(array('a', 'b', 2, 3, 4, 'f'), $queue->asArray());
	}

	/**
	 * @test
	 * @expectedException \OutOfBoundsException
	 */
	public function testGetInvalidRange()
	{
		$queue = new Queue(array(0, 1, 2, 3));
		$queue->get(4);
	}

	/** @test */
	public function testQueueOffsetUnset()
	{
		$queue = new Queue(array(0, 1, 2, 3, 4));
		$queue->offsetUnset(2);
		$this->assertEquals(array(0, 1, 3, 4), $queue->asArray());
		$queue->offsetUnset(0);
		$this->assertEquals(array(1, 3, 4), $queue->asArray());
		$queue->offsetUnset(2);
		$this->assertEquals(array(1, 3), $queue->asArray());

		$queue->shift();
		$queue->pushAll(array(4, 5, 6, 7, 8, 9));
		$this->assertEquals(range(3, 9), $queue->asArray());
		$queue->offsetUnset(1);
		$this->assertEquals(array(3, 5, 6, 7, 8, 9), $queue->asArray());
	}
}

