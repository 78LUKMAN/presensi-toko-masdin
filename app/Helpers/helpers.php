<?php

if (!function_exists('formatWorkingHours')) {
    /**
     * Format decimal hours into a human-readable string: "Xj Ym".
     *
     * - Negative or null values return '-' (data tidak valid).
     * - Zero returns '0j 0m'.
     * - Positive values are broken down into hours + minutes.
     *
     * @param  float|int|null  $decimalHours  e.g. 9.5 means 9 hours 30 minutes
     * @param  bool  $long  If true, returns "X jam Y menit" instead of "Xj Ym"
     * @return string
     */
    function formatWorkingHours($decimalHours, bool $long = false): string
    {
        if ($decimalHours === null || $decimalHours < 0) {
            return '-';
        }

        $totalMinutes = (int) round($decimalHours * 60);
        $hours = intdiv($totalMinutes, 60);
        $minutes = $totalMinutes % 60;

        if ($long) {
            return "{$hours} jam {$minutes} menit";
        }

        return sprintf('%02d:%02d', $hours, $minutes);
    }
}

if (!function_exists('formatWorkingHoursFromMinutes')) {
    /**
     * Format raw minutes into a human-readable string: "Xj Ym".
     *
     * @param  int|null  $totalMinutes
     * @param  bool  $long
     * @return string
     */
    function formatWorkingHoursFromMinutes($totalMinutes, bool $long = false): string
    {
        if ($totalMinutes === null || $totalMinutes < 0) {
            return '-';
        }

        return formatWorkingHours($totalMinutes / 60, $long);
    }
}
