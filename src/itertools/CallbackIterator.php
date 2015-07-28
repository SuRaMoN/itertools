<?php

namespace itertools;


class CallbackIterator extends CurrentCachedIterator
{
    protected $maxInvocations;
    protected $callback;
    protected $breakCallback;
    protected $invocationCount;
    protected $breakCalled;

    public function __construct($callback, $maxInvocations = INF)
    {
        parent::__construct(new RepeatIterator(true));
        $this->callback = $callback;
        $this->maxInvocations = $maxInvocations;
        $breakCalled = &$this->breakCalled;
        $this->breakCallback = function() use (&$breakCalled) {
            $breakCalled = true;
        };
    }

    public function rewind()
    {
        $this->breakCalled = false;
        $this->invocationCount = 0;
        parent::rewind();
    }

    public function next()
    {
        $this->invocationCount += 1;
        parent::next();
    }

    public function uncachedCurrent()
    {
        return call_user_func($this->callback, $this->breakCallback);
    }

    public function valid()
    {
        return $this->invocationCount < $this->maxInvocations && ! $this->breakCalled;
    }
}

