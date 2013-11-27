<?php

namespace kartik\helpers;

/**
 * Collection of useful helpers for Yii Applications
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 *
 */
class Enum extends \yii\helpers\Inflector {

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

    /**
     * Check if a variable is empty or not set.
     * @param reference $var variable to perform the check
     * @return boolean
     */
    public static function isEmpty(&$var) {
        return is_array($var) ? empty($var) : (!isset($var) || (strlen($var) == 0));
    }

    /**
     * Properize a string for possessive punctuation.
     * e.g. 
     * 		properize("Chris"); //returns Chris'
     * 		properize("David"); //returns David's
     * @param string $string input string
     */
    public static function properize($string) {
        $string = preg_replace('/\s+(.*?)\s+/', '*\1*', $string);
        return $string . '\'' . ($string[strlen($string) - 1] != 's' ? 's' : '');
    }

    /**
     * Get time elapsed (Facebook Style)
     * @param string $fromTime start date time 
     * @param string $toTime end date time (defaults to current system time)
     * @return string
     */
    public static function timeElapsed($fromTime = null, $toTime = null) {
        if ($fromTime == null) {
            $elapsed = null;
        }
        else {
            $fromTime = strtotime($fromTime);
            $toTime = ($toTime == null) ? time() : (int) $toTime;
            $diff = $toTime - $fromTime;

            // intervals in seconds
            $intervals = [
                'year' => 31556926,
                'month' => 2629744,
                'week' => 604800,
                'day' => 86400,
                'hour' => 3600,
                'minute' => 60
            ];

            // now we just find the difference
            if ($diff <= 0) {
                $elapsed = 'a moment ago';
            }
            elseif ($diff < 60) {
                $elapsed = $diff == 1 ? $diff . ' second ago' : $diff . ' seconds ago';
            }
            elseif ($diff >= 60 && $diff < $intervals['hour']) {
                $diff = floor($diff / $intervals['minute']);
                $elapsed = $diff == 1 ? $diff . ' minute ago' : $diff . ' minutes ago';
            }
            elseif ($diff >= $intervals['hour'] && $diff < $intervals['day']) {
                $diff = floor($diff / $intervals['hour']);
                $elapsed = $diff == 1 ? $diff . ' hour ago' : $diff . ' hours ago';
            }
            elseif ($diff >= $intervals['day'] && $diff < $intervals['week']) {
                $diff = floor($diff / $intervals['day']);
                $elapsed = $diff == 1 ? $diff . ' day ago' : $diff . ' days ago';
            }
            elseif ($diff >= $intervals['week'] && $diff < $intervals['month']) {
                $diff = floor($diff / $intervals['week']);
                $elapsed = $diff == 1 ? $diff . ' week ago' : $diff . ' weeks ago';
            }
            elseif ($diff >= $intervals['month'] && $diff < $intervals['year']) {
                $diff = floor($diff / $intervals['month']);
                $elapsed = $diff == 1 ? $diff . ' month ago' : $diff . ' months ago';
            }
            elseif ($diff >= $intervals['year']) {
                $diff = floor($diff / $intervals['year']);
                $elapsed = $diff == 1 ? $diff . ' year ago' : $diff . ' years ago';
            }
        }
        return $elapsed;
    }

    /**
     * Format and convert a Bytes to its 
     * optimal higher metric unit
     * @param double $bytes number of bytes
     * @param integer $precision the number of decimal places to round off
     * @return string
     */
    public static function formatBytes($bytes, $precision = 2) {
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
    protected static function convertTri($num, $tri) {
        // chunk the number, ...rxyy
        $r = (int) ($num / 1000);
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
        }
        else {
            $str .= static::$tens[(int) ($y / 10)] . static::$ones[$y % 10];
        }
        
        // add triplet modifier only if there
        // is some output to be modified...
        if ($str != "") {
            $str .= static::$triplets[$tri];
        }
        
        // continue recursing?
        if ($r > 0) {
            return convertTri($r, $tri + 1) . $str;
        }
        else {
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
    public static function numToWords($num) {
        $num = (int) $num; // make sure it's an integer

        if ($num < 0) {
            return "negative " . convertTri(-$num, 0);
        }

        if ($num == 0) {
            return "zero";
        }

        return static::convertTri($num, 0);
    }

}
