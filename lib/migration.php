<?php


namespace Toolbox\Core;


use Toolbox\Core\Logger\LoggerTrait;

class Migration
{
    use LoggerTrait;
    use ContainerTrait;
    private static $path = '/local/migrations/';

    public static function run($name)
    {
        $m = new self();
        $m->exec($name);
    }

    public function exec($name)
    {
        \CModule::IncludeModule('main');
        \CModule::IncludeModule('catalog');
        \CModule::IncludeModule('iblock');
        \CModule::IncludeModule('sale');


        if (file_exists(self::getFileName($name))) {
            include self::getFileName($name);
            echo "\r\n Migration executed";
        } else {
            echo "\r\n Migration file not exist";
        }
    }

    /**
     * get
     */

    public function getFileName($name)
    {
        return $_SERVER['DOCUMENT_ROOT'] . self::$path . $name . '.php';
    }
}
