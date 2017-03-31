<?php

namespace Kanel\Timer;

use Kanel\Timer\Exception\TimerException;

/**
 * Class Timer
 * @package Kanel\Timer
 */
class Timer
{
    const FROM_LAST_LAP = 'last_lap';
    const NONE = 'none';
    const STARTED = 'started';
    const STOPPED = 'stopped';

    protected static $timers;
    protected static $status;
    protected static $statistics;
    protected static $laps;

    /**
     * function that starts/restarts the timer
     */
    public static function start()
    {
        self::reset();
        self::$timers[] = microtime(true);
        self::$status = self::STARTED;
    }

    /**
     * Functions that simulates a lap
     * returns either the duration between the time it started and the time lap was called or between this lap and the previous one
     * @param bool $fromLastLap
     * @return float
     * @throws TimerException
     */
    public static function lap(string $fromLastLap = ''): float
    {
        if (self::$status !== self::STARTED) {
            throw new TimerException('Timer is not started', 500);
        }

        self::$laps++;
        self::$timers[] = microtime(true);

        return self::statistics($fromLastLap);
    }

    /**
     * Functions that stops the timer
     * returns the duration between the start and the stop
     * @param bool $fromLastLap set to true if you want the duration of the stop to be calculated from the last lap (or start if no lap)
     * @param bool $fromLastLap
     * @return mixed
     * @throws TimerException
     */
    public static function stop(string $fromLastLap = ''): float
    {
        if (self::$status !== self::STARTED) {
            throw new TimerException('Timer is not started', 500);
        }

        self::$laps++;
        self::$timers[] = microtime(true);
        self::$status = self::STOPPED;

        return self::statistics($fromLastLap);
    }

    /**
     * calculates the duration between the start and the last lap or stop
     * @param string $fromLastLap
     * @return float
     */
    protected static function statistics(string $fromLastLap = ''): float
    {
        if ($fromLastLap !== self::FROM_LAST_LAP || self::$laps < 1 ) {
            $duration = self::$timers[self::$laps] - self::$timers[0];
        } else {
            $duration = self::$timers[self::$laps] - self::$timers[self::$laps - 1];
        }

        self::$statistics[] = $duration;

        return $duration;
    }

    /**
     * Resets everything
     */
    public static function reset()
    {
        self::$laps = 0;
        self::$timers = [];
        self::$status = self::NONE;
    }

    /**
     * return the start time in milliseconds
     * @return float
     */
    public static function getStartTime(): float
    {
        return self::$timers[0]?? 0;
    }

    /**
     * returns the list of times set (each lap creates an entry, a stop creates one)
     * @return array
     */
    public static function getTimers(): array
    {
        return self::$timers;
    }

    /**
     * returns all the metrics calculated throughout the Timer life
     * @return array
     */
    public static function getStatistics(): array
    {
        return self::$statistics;
    }

    /**
     * gets the current status of the time :
     * @return string values are started|stopped
     */
    public static function getStatus(): string
    {
        return self::$status;
    }
}