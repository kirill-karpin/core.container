<?php


namespace Toolbox\Core\Soap;


use Toolbox\Core\Logger\LoggerTrait;

class SoapClient extends \SoapClient
{
    use LoggerTrait;



    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $data = parent::__doRequest($request, $location, $action, $version, $one_way);
        return $data;
    }

}