<?php


namespace Toolbox\Core;


class CoreConfig
{
    const NAME = 'Toolbox';
    const ERROR_LOG = '/local/var/log/' . self::NAME . '.error.log';
    const MAIN_LOG =  '/local/var/log/' . self::NAME . '.main.log';
}