<?php

namespace Toolbox\Core\Logger;


use Monolog\Registry;

// TODO: Review and redesign logging with levels and contexts
trait LoggerTrait
{
    public static function getLogger($name = 'main')
    {
        return Registry::getInstance($name);
    }

    public static function log($message, $context = null, $name = 'main')
    {
        $logger = self::getLogger($name);
        try {
            $logger->debug($message, [$context]);
        } catch (\Exception $e) {

        }
    }

    public static function debug($data, $message = '')
    {
        self::log($message, [$data]);
    }

    public static function info($message,  $context = null, $name = 'main')
    {
        $logger = self::getLogger($name);

        try {
            $logger->info($message, [$context]);
        } catch (\Exception $e) {

        }
    }

    public static function error($message, $context = null)
    {
        $logger = self::getLogger('error');
        $logger->error($message, [$context]);
    }
}