<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014
 * @package yii2-helpers
 * @version 1.2.0
 */

namespace kartik\helpers;

use yii\base\InvalidConfigException;

/**
 * Collection of useful helper functions for Yii Applications
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 *
 */
class Enum extends \yii\helpers\Inflector
{
    /* list of ones */
    public static $ones = [
        "",
        " one",
        " two",
        " three",
        " four",
        " five",
        " six",
        " seven",
        " eight",
        " nine",
        " ten",
        " eleven",
        " twelve",
        " thirteen",
        " fourteen",
        " fifteen",
        " sixteen",
        " seventeen",
        " eighteen",
        " nineteen"
    ];

    /* list of tens */
    public static $tens = array(
        "",
        "",
        " twenty",
        " thirty",
        " forty",
        " fifty",
        " sixty",
        " seventy",
        " eighty",
        " ninety"
    );

    /* list of triplets */
    public static $triplets = array(
        "",
        " thousand",
        " million",
        " billion",
        " trillion",
        " quadrillion",
        " quintillion",
        " sextillion",
        " septillion",
        " octillion",
        " nonillion"
    );

    /* list of months */
    public static $months = [
        1 => 'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];

    /* list of days of week */
    public static $days = [
        1 => 'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
    ];

    /* time intervals in seconds */
    public static $intervals = [
        'year' => 31556926,
        'month' => 2629744,
        'week' => 604800,
        'day' => 86400,
        'hour' => 3600,
        'minute' => 60,
        'second' => 1
    ];

    /**
     * Check if a variable is empty or not set.
     *
     * @param reference $var variable to perform the check
     * @return boolean
     */
    public static function isEmpty(&$var)
    {
        return is_array($var) ? empty($var) : (!isset($var) || (strlen($var) == 0));
    }

    /**
     * Check if a value exists in the array. This method is faster
     * in performance than the built in PHP in_array method.
     *
     * @param string $needle the value to search
     * @param array $haystack the array to scan
     * @return boolean
     */
    public static function inArray($needle, $haystack)
    {
        $flippedHaystack = array_flip($haystack);
        return isset($flippedHaystack[$needle]);
    }

    /**
     * Properize a string for possessive punctuation.
     * e.g.
     *     properize("Chris"); //returns Chris'
     *     properize("David"); //returns David's
     *
     * @param string $string input string
     */
    public static function properize($string)
    {
        $string = preg_replace('/\s+(.*?)\s+/', '*\1*', $string);
        return $string . '\'' . ($string[strlen($string) - 1] != 's' ? 's' : '');
    }

    /**
     * Get time elapsed (Facebook Style)
     *
     * Example Output(s):
     *     10 hours ago
     *
     * @param string $fromTime start date time
     * @param boolean $human if true returns an approximate human friendly output
     * If set to false will attempt an exact conversion of time intervals.
     * @param string $toTime end date time (defaults to current system time)
     * @param string $append the string to append for the converted elapsed time
     * @return string
     */
    public static function timeElapsed($fromTime = null, $human = true, $toTime = null, $append = ' ago')
    {
        $elapsed = '';
        if ($fromTime != null) {
            $fromTime = strtotime($fromTime);
            $toTime = ($toTime == null) ? time() : (int)$toTime;
            $diff = $toTime - $fromTime;
            $intervals = static::$intervals;

            if ($human) {
                // now we just find the difference
                if ($diff <= 0) {
                    $elapsed = 'a moment ago';
                } elseif ($diff < 60) {
                    $elapsed = $diff == 1 ? $diff . ' second ago' : $diff . ' seconds' . $append;
                } elseif ($diff >= 60 && $diff < $intervals['hour']) {
                    $diff = floor($diff / $intervals['minute']);
                    $elapsed = $diff == 1 ? $diff . ' minute ago' : $diff . ' minutes' . $append;
                } elseif ($diff >= $intervals['hour'] && $diff < $intervals['day']) {
                    $diff = floor($diff / $intervals['hour']);
                    $elapsed = $diff == 1 ? $diff . ' hour ago' : $diff . ' hours' . $append;
                } elseif ($diff >= $intervals['day'] && $diff < $intervals['week']) {
                    $diff = floor($diff / $intervals['day']);
                    $elapsed = $diff == 1 ? $diff . ' day ago' : $diff . ' days' . $append;
                } elseif ($diff >= $intervals['week'] && $diff < $intervals['month']) {
                    $diff = floor($diff / $intervals['week']);
                    $elapsed = $diff == 1 ? $diff . ' week ago' : $diff . ' weeks ago';
                } elseif ($diff >= $intervals['month'] && $diff < $intervals['year']) {
                    $diff = floor($diff / $intervals['month']);
                    $elapsed = $diff == 1 ? $diff . ' month ago' : $diff . ' months' . $append;
                } elseif ($diff >= $intervals['year']) {
                    $diff = floor($diff / $intervals['year']);
                    $elapsed = $diff == 1 ? $diff . ' year ago' : $diff . ' years' . $append;
                }
            } else {
                $elapsed = static::time2String($diff, $intervals) . $append;
            }
        }
        return $elapsed;
    }

    /**
     * Get elapsed time converted to string
     *
     * Example Output:
     *    1 year 5 months 3 days ago
     *
     * @param integer $timeline elapsed number of seconds
     * @param array $intervals configuration of time intervals in seconds
     * @return string
     */
    protected static function time2String($timeline, $intervals)
    {
        $output = '';
        foreach ($intervals AS $name => $seconds) {
            $num = floor($timeline / $seconds);
            $timeline -= ($num * $seconds);
            if ($num > 0) {
                $output .= $num . ' ' . $name . (($num > 1) ? 's' : '') . ' ';
            }
        }
        return trim($output);
    }

    /**
     * Get time remaining (Facebook Style)
     *
     * Example Output(s):
     *     10 hours to go
     *
     * @param string $futureTime future date time
     * @param boolean $human if true returns an approximate human friendly output
     * If set to false will attempt an exact conversion of time intervals.
     * @param string $currentTime current date time (defaults to current system time)
     * @param string $append the string to append for the converted elapsed time
     * (default: 'until the deadline')
     * @return string
     */
    public static function timeRemaining($futureTime = null, $human = true, $currentTime = null, $append = ' until the deadline')
    {
        $remaining = '';
        if ($futureTime != null) {
            $futureTime = strtotime($futureTime);
            $currentTime = ($currentTime == null) ? time() : (int)$currentTime;
            $diff = $futureTime - $currentTime;
            $intervals = static::$intervals;

            if ($human) {
                // now we just find the difference
                if ($diff <= 0) {
                    $remaining = 'a moment to go';
                } elseif ($diff < 60) {
                    $remaining = $diff == 1 ? $diff . ' second to go' : $diff . ' seconds' . $append;
                } elseif ($diff >= 60 && $diff < $intervals['hour']) {
                    $diff = floor($diff / $intervals['minute']);
                    $remaining = $diff == 1 ? $diff . ' minute to go' : $diff . ' minutes' . $append;
                } elseif ($diff >= $intervals['hour'] && $diff < $intervals['day']) {
                    $diff = floor($diff / $intervals['hour']);
                    $remaining = $diff == 1 ? $diff . ' hour to go' : $diff . ' hours' . $append;
                } elseif ($diff >= $intervals['day'] && $diff < $intervals['week']) {
                    $diff = floor($diff / $intervals['day']);
                    $remaining = $diff == 1 ? $diff . ' day to go' : $diff . ' days' . $append;
                } elseif ($diff >= $intervals['week'] && $diff < $intervals['month']) {
                    $diff = floor($diff / $intervals['week']);
                    $remaining = $diff == 1 ? $diff . ' week to go' : $diff . ' weeks to go';
                } elseif ($diff >= $intervals['month'] && $diff < $intervals['year']) {
                    $diff = floor($diff / $intervals['month']);
                    $remaining = $diff == 1 ? $diff . ' month to go' : $diff . ' months' . $append;
                } elseif ($diff >= $intervals['year']) {
                    $diff = floor($diff / $intervals['year']);
                    $remaining = $diff == 1 ? $diff . ' year to go' : $diff . ' years' . $append;
                }
            } else {
                $remaining = static::time2String($diff, $intervals) . $append;
            }
        }
        return $remaining;
    }

    /**
     * Format and convert "bytes" to its
     * optimal higher metric unit
     *
     * @param double $bytes number of bytes
     * @param integer $precision the number of decimal places to round off
     * @return string
     */
    public static function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Recursive function used in number to words conversion.
     * Converts three digits per pass.
     *
     * @param double $num the source number
     * @param double $tri the three digits converted per pass.
     * @return string
     */
    protected static function convertTri($num, $tri)
    {
        // chunk the number, ...rxyy
        $r = (int)($num / 1000);
        $x = ($num / 100) % 10;
        $y = $num % 100;

        // init the output string
        $str = "";

        // do hundreds
        if ($x > 0) {
            $str = static::$ones[$x] . " hundred";
        }

        // do ones and tens
        if ($y < 20) {
            $str .= static::$ones[$y];
        } else {
            $str .= static::$tens[(int)($y / 10)] . static::$ones[$y % 10];
        }

        // add triplet modifier only if there
        // is some output to be modified...
        if ($str != "") {
            $str .= static::$triplets[$tri];
        }

        // continue recursing?
        if ($r > 0) {
            return static::convertTri($r, $tri + 1) . $str;
        } else {
            return $str;
        }
    }

    /**
     * Number to words conversion. Returns the number
     * converted as an anglicized string.
     *
     * @param double $num the source number
     * @return string
     */
    public static function numToWords($num)
    {
        $num = (int)$num; // make sure it's an integer

        if ($num < 0) {
            return "negative " . static::convertTri(-$num, 0);
        }

        if ($num == 0) {
            return "zero";
        }

        return static::convertTri($num, 0);
    }

    /**
     * Generates a list of years
     *
     * @param integer $from the start year
     * @param integer $to the end year
     * @param boolean $keys whether to set the array keys same as the values (defaults to false)
     * @param boolean $desc whether to sort the years descending (defaults to true)
     * @return array
     * @throws InvalidConfigException if $to < $from
     */
    public static function yearList($from, $to = null, $keys = false, $desc = true)
    {
        if (static::isEmpty($to)) {
            $to = intval(date("Y"));
        }
        if ($to >= $from) {
            $years = ($desc) ? range($to, $from) : range($from, $to);
            return $keys ? array_combine($years, $years) : $years;
        } else {
            throw new InvalidConfigException("The 'year to' parameter must exceed 'year from'.");
        }
    }

    /**
     * Generate a date picker array list for Gregorian Calendar.
     * Note that first week day is always Sunday.
     *
     * @param string $unit the date unit ('date', 'day', 'month')
     * @param boolean $abbr whether to return abbreviated day or month
     * @param integer $maxDay the maximum date if $unit = 'date'
     * @return array
     * @throws InvalidConfigException if $unit passed is invalid or $maxDay < 1
     */
    public static function dateList($unit, $abbr = false, $maxDay = 31)
    {
        switch($unit)
        {
            case 'date':
                if ($maxDay < 1) {
                    throw new InvalidConfigException("Invalid maxDay passed. Must be greater or equal than 1");
                }
                return range(1, $maxDay);
            
            case 'day':
                return self::dayList($abbr);
            
            case 'month':
                $return = static::$months;
                break;
            
            default:
                throw new InvalidConfigException("Invalid date unit passed. Must be 'date', 'day', or 'month'.");
        }
        
        if ($abbr) {
            $substr = function ($element) {
                return substr($element, 0, 3);
            };
            
            return array_map($substr, $return);
        }
        
        return $return;
    }
    
    /**
     * Generate a day array list for Gregorian calendar
     * @param boolean $abbr whether to return abbreviated day or month
     * @param boolean $mondayFirstDay to return Monday first instead of Sunday
     * @return array List of days
     */
    public static function dayList( $abbr = false, $mondayFirstDay = false )
    {
        $days = self::$days;

        if ($mondayFirstDay) {
            $lastDay = array_shift($days);
            $days[] = $lastDay;
            // Re-index from 1
            $days = array_combine(range(1, count($days)), array_values($days));
        }
        
        if ($abbr) {
            $substr = function ($element) {
                return substr($element, 0, 3);
            };
            
            return array_map($substr, $days);
        }
        
        return $days;
    }

    /**
     * Generate a number list string array
     * @param integer $to value
     * @param integer $from value
     * @param integer $interval value
     * @return type
     */
    protected static function getNumberList( $to, $from = null, $interval = null )
    {
        $result = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09'];
        
        if( $to > 9 ) {
            $result = array_merge($result, range(10, $to));
            
        } elseif ($to < 9 ) {
            $result = array_slice( $result, 0, $to + 1 );
        }
        
        if ($from) {
            if ($from > $to) {
                throw new InvalidConfigException('Invalid use : $from must be less or equal to $to value');
            } 
            
            $result = array_slice($result, $from + 1);
        } else {
            $from = 0;
        }
        
        if ($interval) {
            if ($interval < 1) {
                throw new InvalidConfigException('Invalid use : $interval has to be greater than 0');
                
            } elseif ($interval > 1) {

                if ($to >= $interval) {
                    $newResult = [];
                    foreach( range($from, $to, $interval) as $key ) {
                        $newResult[] = $result[$key];
                    }
                
                    return $newResult;
                }

                // Drop last value ($interval > $to)
                array_pop( $result );
                return $result;
            }
        }
        
        return $result;
    }
    
    /**
     * Generate a time picker array list
     *
     * @param string $unit the time unit ('hour', 'min', 'sec', 'ms')
     * @param integer $from value (max depending on unit)
     * @param integer $to value (max depending on unit)
     * @param integer $interval of values
     * @return array of values
     * @throws InvalidConfigException if $unit passed is invalid
     */
    public static function timeList($unit, $from = null, $to = null, $interval = null)
    {
        switch ($unit) {
            case 'hour':
                $defaultTo = $maxTo = 23;
                break;
            
            case 'min':
            case 'sec':
                $defaultTo = $maxTo = 59;
                break;
            
            case 'ms':
                $defaultTo = 1000;
                break;
            
            default:
                throw new InvalidConfigException("Invalid time unit passed. Must be 'hour', 'min', 'sec', or 'ms'.");
        }
        
        if ($to) {
            if ($to < 0) {
                throw new InvalidConfigException('$to value can\'t be less than 0 for ' . $unit . ' unit.');
            }
            if ($maxTo && $to > $maxTo) {
                throw new InvalidConfigException('$to value can\'t be more than ' . $maxTo . ' for ' . $unit . ' unit.');
            }
        } else { $to = $defaultTo; }
        
        return self::getNumberList( $to, $from, $interval );
    }

    /**
     * Generates a boolean list
     *
     * @param string $true the label for the true value
     * @param string $false the label for the false value
     * @return array
     */
    public static function boolList($true = 'Yes', $false = 'No')
    {
        return [
            false => $false,    // == 0
            true => $true,      // == 1
        ];
    }

    /**
     * Parses and returns a variable type
     *
     * @param string $var the variable to be parsed
     * @return string
     */
    public static function getType($var)
    {
        if (is_array($var)) {
            return 'array';
        } elseif (is_object($var)) {
            return 'object';
        } elseif (is_resource($var)) {
            return 'resource';
        } elseif (is_null($var)) {
            return 'NULL';
        } elseif (is_bool($var)) {
            return 'boolean';
        } elseif (is_float($var) || (is_numeric(str_replace(',', '', $var)) && strpos($var, '.') > 0 && is_float((float)str_replace(',', '', $var)))) {
            return 'float';
        } elseif (is_int($var) || (is_numeric($var) && is_int((int)$var))) {
            return 'integer';
        } elseif (is_scalar($var) && strtotime($var) !== false) {
            return 'datetime';
        } elseif (is_scalar($var)) {
            return 'string';
        }
        return 'unknown';
    }

    /**
     * Convert a PHP array to HTML table
     *
     * @param array $array the associative array to be converted
     * @param boolean $transpose whether to show keys as rows instead of columns.
     * This parameter should be used only for a single dimensional associative array.
     * If used for a multidimensional array, the sub array will be imploded as text.
     * @param boolean $recursive whether to recursively generate tables for multi-dimensional arrays
     * @param boolean $typeHint whether to show the data type as a hint
     * @param string $null the content to display for blank cells
     * @param array $tableOptions the HTML attributes for the table
     * @param array $keyOptions the HTML attributes for the array key
     * @param array $valueOptions the HTML attributes for the array value
     * @return string|boolean
     */
    public static function array2table($array, $transpose = false, $recursive = false, $typeHint = true, $tableOptions = ['class' => 'table table-bordered table-striped'], $keyOptions = [], $valueOptions = ['style' => 'cursor: default; border-bottom: 1px #aaa dashed;'], $null = '<span class="not-set">(not set)</span>')
    {
        // Sanity check
        if (empty($array) || !is_array($array)) {
            return false;
        }

        // Start the table
        $table = Html::beginTag('table', $tableOptions) . "\n";

        // The header
        $table .= "\t<tr>";

        if ($transpose) {
            foreach ($array as $key => $value) {
                if ($typeHint) {
                    $valueOptions['title'] = self::getType(strtoupper($value));
                }

                if (is_array($value)) {
                    $value = '<pre>' . print_r($value, true) . '</pre>';
                } else {
                    $value = Html::tag('span', $value, $valueOptions);
                }
                $table .= "\t\t<th>" . Html::tag('span', $key, $keyOptions) . "</th>" .
                    "<td>" . $value . "</td>\n\t</tr>\n";
            }
            $table .= "</table>";
            return $table;
        }

        if (!isset($array[0]) || !is_array($array[0])) {
            $array = array($array);
        }
        // Take the keys from the first row as the headings
        foreach (array_keys($array[0]) as $heading) {
            $table .= '<th>' . Html::tag('span', $heading, $keyOptions) . '</th>';
        }
        $table .= "</tr>\n";

        // The body
        foreach ($array as $row) {
            $table .= "\t<tr>";
            foreach ($row as $cell) {
                $table .= '<td>';

                // Cast objects
                if (is_object($cell)) {
                    $cell = (array)$cell;
                }

                if ($recursive === true && is_array($cell) && !empty($cell)) {
                    // Recursive mode
                    $table .= "\n" . array2table($cell, true, true) . "\n";
                } else {
                    if (!is_null($cell) && is_bool($cell)) {
                        $val = $cell ? 'true' : 'false';
                        $type = 'boolean';
                    } else {
                        $chk = (strlen($cell) > 0);
                        $type = $chk ? self::getType($cell) : 'NULL';
                        $val = $chk ? htmlspecialchars((string)$cell) : $null;
                    }
                    if ($typeHint) {
                        $valueOptions['title'] = $type;
                    }
                    $table .= Html::tag('span', $val, $valueOptions);
                }

                $table .= '</td>';
            }

            $table .= "</tr>\n";
        }

        $table .= '</table>';
        return $table;
    }

    /**
     * Gets the user's IP address
     *
     * @param boolean $filterLocal whether to filter local & LAN IP (defaults to true)
     * @return string
     */
    public static function userIP($filterLocal = true)
    {
        $ipSources = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        foreach ($ipSources as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
                    if ($filterLocal) {
                        $checkFilter = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
                        if ($checkFilter !== false) {
                            return $ip;
                        }
                    } else {
                        return $ip;
                    }
                }
            }
        }
        return 'Unknown';
    }

    /**
     * Gets basic browser information
     *
     * @param boolean $common show common browsers only
     * @param array $browsers the list of browsers
     * @param string $agent user agent
     * @return array the browser information
     */
    public static function getBrowser($common = false, $browsers = [], $agent = null)
    {
        if ($agent === null) {
            $agent = $_SERVER['HTTP_USER_AGENT'];
        }
        if ($common) {
            $browsers = [
                "opera" => "Opera",
                "chrome" => "Google Chrome",
                "safari" => "Safari",
                "firefox" => "Mozilla Firefox",
                "msie" => "Microsoft Internet Explorer",
                "mobile safari" => "Mobile Safari",
            ];
        } elseif (empty($browsers)) {
            $browsers = [
                "opera" => "Opera",
                "maxthon" => "Maxthon",
                "seamonkey" => "Mozilla Sea Monkey",
                "arora" => "Arora",
                "avant" => "Avant",
                "omniweb" => "Omniweb",
                "epiphany" => "Epiphany",
                "chromium" => "Chromium",
                "galeon" => "Galeon",
                "puffin" => "Puffin",
                "fennec" => "Mozilla Firefox Fennec",
                "chrome" => "Google Chrome",
                "mobile safari" => "Mobile Safari",
                "safari" => "Apple Safari",
                "firefox" => "Mozilla Firefox",
                "iemobile" => "Microsoft Internet Explorer Mobile",
                "msie" => "Microsoft Internet Explorer",
                "konqueror" => "Konqueror",
                "amaya" => "Amaya",
                "omniweb" => "Omniweb",
                "netscape" => "Netscape",
                "mosaic" => "Mosaic",
                "netsurf" => "NetSurf",
                "netfront" => "NetFront",
                "minimo" => "Minimo",
                "blackberry" => "Blackberry",
            ];
        }
        $info = [
            'agent' => $agent,
            'code' => 'other',
            'name' => 'Other',
            'version' => "?",
            'platform' => 'Unknown'
        ];

        if (preg_match('/iphone|ipod|ipad/i', $agent)) {
            $info['platform'] = 'ios';
        } elseif (preg_match('/android/i', $agent)) {
            $info['platform'] = 'android';
        } elseif (preg_match('/symbian/i', $agent)) {
            $info['platform'] = 'symbian';
        } elseif (preg_match('/maemo/i', $agent)) {
            $info['platform'] = 'maemo';
        } elseif (preg_match('/palm/i', $agent)) {
            $info['platform'] = 'palm';
        } elseif (preg_match('/linux/i', $agent)) {
            $info['platform'] = 'linux';
        } elseif (preg_match('/mac/i', $agent)) {
            $info['platform'] = 'mac';
        } elseif (preg_match('/win/i', $agent)) {
            $info['platform'] = 'windows';
        } elseif (preg_match('/x11|bsd|sun/i', $agent)) {
            $info['platform'] = 'unix';
        }

        foreach ($browsers as $code => $name) {
            if (preg_match("/{$code}/i", $agent)) {
                $info['code'] = $code;
                $info['name'] = $name;
                $info['version'] = static::getBrowserVer($agent, $code);
                return $info;
            }
        }
        return $info;
    }

    /**
     * Returns browser version
     *
     * @param string $agent
     * @param browser $code
     * @return float
     */
    protected static function getBrowserVer($agent, $code)
    {
        $version = '?';
        $pattern = '#(?<browser>' . $code . ')[/v ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';
        if ($code == 'blackberry') {
            $pattern = '#(?<browser>' . $code . ')[/v0-9 ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';
        }
        if (preg_match_all($pattern, strtolower($agent), $matches)) {
            $i = count($matches['browser']) - 1;
            $ver = [$matches['browser'][$i] => $matches['version'][$i]];
            $version = empty($ver[$code]) ? '?' : $ver[$code];
        }
        return $version;
    }

}
