<?php
class Logger
{
    public static function info(string $message): void
    {
        file_put_contents('php://stdout', "[INFO] $message\n");
    }

    public static function error(string $message): void
    {
        file_put_contents('php://stderr', "[ERROR] $message\n");
    }

    public static function warn(string $message): void
    {
        file_put_contents('php://stderr', "[WARNING] $message\n");
    }

    public static function debug(string $message): void
    {
        file_put_contents('php://stdout', "[DEBUG] $message\n");
    }
}
