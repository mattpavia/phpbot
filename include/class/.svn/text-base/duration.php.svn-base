<?php
/*
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.2.1
 * @link          http://aidanlister.com/repos/v/Duration.php
 */
class Duration {
    function toString ($duration, $periods = null) {
        if (!is_array($duration)) {
            $duration = Duration::int2array($duration, $periods);
        }
        return Duration::array2string($duration);
    }
    function int2array ($seconds, $periods = null) {        
        if (!is_array($periods)) {
            $periods = array (
                    'years'     => 31556926,
                    'months'    => 2629743,
                    'weeks'     => 604800,
                    'days'      => 86400,
                    'hours'     => 3600,
                    'minutes'   => 60,
                    'seconds'   => 1
                    );
        }
        $seconds = (float) $seconds;
        foreach ($periods as $period => $value) {
            $count = floor($seconds / $value);
            if ($count == 0) {
                continue;
            }
            $values[$period] = $count;
            $seconds = $seconds % $value;
        }
        if (empty($values)) {
            $values = null;
        }
        return $values;
    }
    function array2string ($duration) {
        if (!is_array($duration)) {
            return false;
        }
        foreach ($duration as $key => $value) {
            $segment_name = substr($key, 0, -1);
            $segment = $value . ' ' . $segment_name; 
            if ($value != 1) {
                $segment .= 's';
            }
            $array[] = $segment;
        }
        $str = implode(', ', $array);
        return $str;
    }
}

?>