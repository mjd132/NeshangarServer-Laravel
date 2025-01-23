<?php
declare(strict_types=1);

if (!function_exists('filesystem')) {

    function isDateIntervalBetween5And120Minutes(DateInterval $interval): bool
    {
        $totalSeconds = ($interval->y * 365 * 24 * 60 * 60) +
            ($interval->m * 30 * 24 * 60 * 60) +
            ($interval->d * 24 * 60 * 60) +
            ($interval->h * 60 * 60) +
            ($interval->i * 60) +
            $interval->s;

        $minSeconds = 5 * 60;    // 5 minutes in seconds
        $maxSeconds = 120 * 60;  // 120 minutes in seconds

        return $totalSeconds >= $minSeconds && $totalSeconds <= $maxSeconds;
    }
}
