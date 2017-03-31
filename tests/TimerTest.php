<?php

namespace Kanel\Timer;

use PHPUnit\Framework\TestCase;
use Kanel\Timer\Exception\TimerException;

function microtime(bool $get_as_float = false): float
{
    if ($get_as_float === true) {
        return TimerTest::$microtime += 1;
    }

    throw new \Exception('wrong microtime usage in Timer context');
}

class TimerTest extends TestCase
{
    public static $microtime;
    public static $memory ;
    protected $timer;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        self::$microtime = 0;
        self::$memory = 0;
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testStart()
    {
        Timer::start();
        $this->assertEquals(Timer::getStatus(), Timer::STARTED);
        $this->assertEquals(Timer::getStartTime(), 1);
        $this->assertEquals(count(Timer::getTimers()), 1);
    }

    public function testStopFail()
    {
        $this->expectException(TimerException::class);
        Timer::reset();
        Timer::stop();
    }

    public function testStop()
    {
        Timer::start();
        Timer::lap();
        Timer::lap();
        $duration = Timer::stop();
        $this->assertEquals(Timer::getStatus(), Timer::STOPPED);
        $this->assertEquals($duration, 3);
        $this->assertEquals(count(Timer::getTimers()), 4);
    }

    public function testStopWithLaps()
    {
        Timer::start();
        Timer::lap();
        Timer::lap();
        $duration = Timer::stop(Timer::FROM_LAST_LAP);
        $this->assertEquals(Timer::getStatus(), Timer::STOPPED);
        $this->assertEquals($duration, 1);
        $this->assertEquals(count(Timer::getTimers()), 4);
    }

    public function testLapFail()
    {
        $this->expectException(TimerException::class);
        Timer::reset();
        Timer::lap();
    }

    public function testLap()
    {
        Timer::start();
        $duration = Timer::lap();
        $this->assertEquals($duration, 1);
        $duration = Timer::lap();
        $this->assertEquals($duration, 2);
        $duration = Timer::lap();
        $this->assertEquals($duration, 3);
        $duration = Timer::lap();
        $this->assertEquals($duration, 4);
        $duration = Timer::lap(Timer::FROM_LAST_LAP);
        $this->assertEquals($duration, 1);
    }

}