<?php


namespace Toolbox\Core\Soap;

use SoapClient;

abstract class BaseSoapEntity
{
    protected $name;

    /**
     * @return SoapClient
     */
    public function getClient()
    {
        return SoapConnectionsPool::getInstance()->getConnect($this->name);
    }
}