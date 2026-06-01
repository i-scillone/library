<?php
namespace MyClasses;

class Net
{
    public static function serverUrl(): string
    {
        return (($_SERVER['HTTPS'] ?? 'off') == 'on' ? 'https' : 'http').
               '://' . $_SERVER['HTTP_HOST'];
    }
    public static function pathOnServer(): string
    {
        return self::serverUrl().dirname($_SERVER['PHP_SELF']);
    }
}