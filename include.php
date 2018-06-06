<?php


use Toolbox\Core\Container;
use Toolbox\Core\Logger\MainLogger;

class_alias('\Toolbox\Core\Container', 'CoreContainer');
class_alias('\Toolbox\Core\Migration', 'CoreMigration');
class_alias('\Toolbox\Core\Logger\MainLogger', 'CoreLogger');

if (class_exists('Bex\Monolog\MonologAdapter')) {
    MainLogger::loadConfiguration();
}

$container = Container::getInstance();

$container->add('user_repository', '\Toolbox\Core\User\UserRepository');
$container->add('user_group_repository', '\Toolbox\Core\User\UserGroup\UserGroupRepository');
$container->add('group_repository', '\Toolbox\Core\User\Group\GroupRepository');
$container->add('file_repository', 'Toolbox\Core\Repository\FileRepository');

$container->add('user_service', '\Toolbox\Core\User\UserService')
    ->withArgument('user_group_repository')
    ->withArgument('group_repository')
    ->withArgument('user_repository');




function dump($var, $die = false, $resetBuffer = false)
{
    if ($resetBuffer){
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
    }

    echo '<pre>';
    print_r($var);
    echo '</pre>';

    if ($die) die;
}
