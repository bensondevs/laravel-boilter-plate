<?php

use Carbon\Carbon;

/**
 * Create carbon instance
 *
 * @param  mixed  $parameter
 * @return Carbon
 */
if (! function_exists('carbon')) {
	function carbon($parameter = null)
	{
        try {
            return ($parameter === null) ?
                new Carbon() :
                new Carbon($parameter);
        } catch (Exception $e) {
        }
    }
}

/**
 * Parse string of date into certain format supplied
 *
 * @param  string  $date
 * @param  string  $format
 * @return string
 */
if (! function_exists('carbon_parse_format')) {
	function carbon_parse_format(string $date, string $format = 'm-d-Y')
	{
		return carbon()->parse($date)->format($format);
	}
}

/**
 * Convert any form of date into certain format
 *
 * @param  mixed  $date
 * @param  string  $format
 * @return string
 */
if (! function_exists('format_datetime')) {
	function format_datetime($date, string $format = 'Y-m-d H:i:s')
	{
		return carbon($date)->format($format);
	}
}

/**
 * Convert string of date into timestamp
 *
 * @param  string  $date
 * @return  \DateTime
 */
if (! function_exists('carbon_parse_timestamp')) {
	function carbon_parse_timestamp(string $date)
	{
		return carbon()->parse($date)->timestamp;
	}
}

/**
 * Convert datetime into formatted date string.
 *
 * @param  DateTime  $datetime
 * @return string
 */
if (! function_exists('carbon_parse_formatted_date_string')) {
	function carbon_parse_formatted_date_string($datetime)
	{
		return carbon()
			->parse($datetime)
			->toFormattedDateString();
	}
}

/**
 * Convert datetime to certain timezone
 *
 * @param  \DateTime|string|null  $datetime
 * @param  string|null            $timezone
 * @return \DateTime
 */
if (! function_exists('convert_timezone')) {
	function convert_timezone($datetime = null, $timezone = null)
	{
		if (is_null($datetime)) {
            return now();
        }

        if (is_null($timezone)) {
            $timezone = config('app.admin_timezone');
        }

        return carbon()->parse($datetime)
            ->timezone($timezone)
            ->format('Y-m-d h:i:s');
	}
}

/**
 * Change timezone of the application
 *
 * @param  \DateTime|string|null  $datetime
 * @param  string|null  $timezone
 * @return \DateTime
 */
if (! function_exists('change_timezone')) {
    function change_timezone($datetime = null, $timezone = null)
    {
        return convert_timezone($datetime, $timezone);
    }
}

/**
 * Get start datetime of certain date string
 *
 * @param  string  $date
 */
if (! function_exists('start_of_day')) {
	function start_of_day(string $date)
	{
		return carbon()->parse($date)->copy()->startOfDay();
	}
}

/**
 * Get end datetime of certain date string
 *
 * @param  string  $date
 */
if (! function_exists('end_of_day')) {
	function end_of_day(string $date)
	{
		return carbon()->parse($date)->copy()->endOfDay();
	}
}

/**
 * Get last week of current day carbon date
 *
 * @return Carbon
 */
if (! function_exists('last_week')) {
	function last_week()
	{
		$now = now()->copy();
		return $now->subDays(7);
	}
}

/**
 * Alias for "last_week"
 *
 * @return Carbon
 */
if (! function_exists('lastWeek')) {
	function lastWeek()
	{
		return last_week();
	}
}

/**
 * Get next week of the current day carbon date
 *
 * @return Carbon
 */
if (! function_exists('next_week')) {
	function next_week()
	{
		$now = now()->copy();
		return $now->addDays(7);
	}
}

/**
 * Alias for "next_week"
 *
 * @return Carbon
 */
if (! function_exists('nextWeek')) {
	function nextWeek()
	{
		return next_week();
	}
}

/**
 * Get datetime of "n" days ago
 *
 * @param  int  $days
 * @return \DateTime
 */
if (! function_exists('few_days_ago')) {
	function few_days_ago(int $days = 3) {
		$now = now()->copy();

		return $now->subDays($days);
	}
}

if (!function_exists('carbon_parse_unix_timestamp')) {
    /**
     * Parse unix timestamp value to carbon instance.
     *
     * @param int $unixTimestamp
     * @return Carbon
     */
    function carbon_parse_unix_timestamp(int $unixTimestamp): Carbon
    {
        return Carbon::createFromTimestamp($unixTimestamp);
    }
}

/**
 * Convert number to month name
 *
 * @param int $number
 * @return string
 */
if (! function_exists('num_to_month_name')) {
	function num_to_month_name(int $number)
	{
		// Possible most minimum number
		if ($number <= 1) $number = 1;

		// If exceed, just take the modulo of 12
		if ($number > 12) {
			$number = ($number % 12);
			$number = ($number == 0) ? 12 : $number;
		}

		$index = $number - 1;
		$monthNames = [
            'January',  'February', 'March', 'April',
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December',
        ];

        return $monthNames[$index] ?? $monthNames[1];
	}
}

/**
 * Get age from the birth date
 *
 * @param  string  $birthDate
 * @return int
 */
if (! function_exists('get_age_from_birth_date')) {
	function get_age_from_birth_date(string|int $birthDate)
	{
		
		$birthDate = gettype($birthDate) == 'string' ? $birthDate :  date('Y-m-d', $birthDate);
        $today = date('Y-m-d');

        $diff = date_diff(date_create($birthDate), date_create($today));

        return $diff->format('%y');
	}
}

/**
 * Get datetime of n years ago
 *
 * @param  int    $yearsAgo
 * @param  string $format
 * @return \DateTime
 */
if (! function_exists('datetime_years_ago')) {
	function datetime_years_ago(int $yearsAgo, string $format = 'Y-m-d')
	{
		return (now()->copy())
			->subYears($yearsAgo)
			->format($format);
	}
}
