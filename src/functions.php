<?php

class LogLevel
{
    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';
}

function logMsg($level, $message, array $context = array())
{
    echo '[' . date('Y-m-d H:i:s') . '][' . posix_getpid() . '][' . $level . '] ' . $message . "\n";
}

function timastampToDate(float $timestamp)
{
    $timestamp = explode('.', $timestamp);
    $date = date('Y-m-d H:i:s', $timestamp[0]);
    if (array_key_exists(1, $timestamp)) {
        $date .= '.' . $timestamp[1];
    }
    return $date;
}