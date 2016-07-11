<?php

function getDatetime($time = false)
{
    if (!$time) {
        $time = time();
    }
    $date = new DateTime();
    $date->setTimestamp($time);
    return $date->format('Y-m-d H:i:s');
}

function getTime($datetime = false)
{
    if ($datetime) {
        return strtotime($datetime);
    } else {
        return time();
    }
}

function getTimePlus($time = false, $plus = 1, $term = "months")
{
    if (!$time) {
        $time = time();
    }
    return strtotime("+".$plus." ".$term, $time);
}