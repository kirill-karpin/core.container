<?php


namespace Toolbox\Core\Logger;

global $_SERVER;

use Bex\Monolog\MonologAdapter;
use Bitrix\Main\Config\Configuration;
use Toolbox\Core\CoreConfig;

class MainLogger extends MonologAdapter
{
    use LoggerTrait;

    public static function loadConfiguration($force = false)
    {
        $config = [
            'monolog' => array(
                'value' => array(
                    'handlers' => array(
                        'error' => array(
                            'class' => '\Monolog\Handler\StreamHandler',
                            'level' => 'DEBUG',
                            'stream' => $_SERVER['DOCUMENT_ROOT'] . CoreConfig::ERROR_LOG
                        ),
                        'main' => array(
                            'class' => '\Monolog\Handler\StreamHandler',
                            'level' => 'DEBUG',
                            'stream' =>  $_SERVER['DOCUMENT_ROOT'] . CoreConfig::MAIN_LOG
                        )
                    ),
                    'loggers' => [
                        'error' => [
                            'handlers' => ['error'],
                        ],
                        'main' => [
                            'handlers' => ['main'],
                        ],
                    ]
                ),
                'readonly' => false
            ),
        ];

        Configuration::getInstance()->add('monolog', $config['monolog']['value']);

        return parent::loadConfiguration($force); // TODO: Change the autogenerated stub
    }


}