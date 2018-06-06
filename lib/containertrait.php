<?php


namespace Toolbox\Core;


trait ContainerTrait
{
    public static function getContainer()
    {
        return $container = Container::getInstance();
    }

}