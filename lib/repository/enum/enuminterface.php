<?php


namespace Toolbox\Core\Repository\Enum;


interface EnumInterface
{
    public function getData();
    
    public function setData();

    public function getById($id);

    public function getByXmlId($xmlId);

    public function __invoke($xmlId);
}