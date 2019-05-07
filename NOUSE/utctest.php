<?php 
// 測試UTC

function get_utc_timestamp()
{
    return time() + mktime(0, 0, 0, 1, 1, 1970);
}

function get_timestamp_from_timezone($tz_offset = 0)
{
    if ($tz_offset > 14 || $tz_offset < -12)
        $tz_offset = 0; // timezone offset range: -12 ~ 14

    return time() + mktime(0, 0, 0, 1, 1, 1970) + ($tz_offset * 60 * 60);
}

/* Test */
echo 'UTC: ' . date('Y-m-d H:i:s', get_timestamp_from_timezone()) . "<BR>";
echo 'TW: ' . date('Y-m-d H:i:s', get_timestamp_from_timezone(8)) . "<BR>";
echo 'JP: ' . date('Y-m-d H:i:s', get_timestamp_from_timezone(9)) . "<BR>";

?>