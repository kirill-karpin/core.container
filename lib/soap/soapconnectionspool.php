<?php


namespace Toolbox\Core\Soap;


use Bitrix\Main\Config\Configuration;

class SoapConnectionsPool
{
    private $connect = [];

    /**
     * @var SoapConnectionsPool The reference to *Singleton* instance of this class
     */
    protected static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return SoapConnectionsPool The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }

    public function getConnect($connectName)
    {
        if (key_exists($connectName, $this->connect)) {
            return $this->connect[$connectName];
        } else {

            if ($config = Configuration::getInstance()->get($connectName)){

                $context = stream_context_create([
                    'ssl' => [
                        // set some SSL/TLS specific options
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ]);

                $this->connect[$connectName] = new SoapClient(
                    $config['url'],
                    array(
                        'login' => $config['login'],
                        'password' => $config['password'],
                        'stream_context' => $context,
                        'trace' => 1
                    )
                );

                return $this->connect[$connectName];
            } else {
                throw new \Exception('Not exist connection');
            }
        }
    }
}