<?php

namespace Toolbox\Core\HLBlock;

use Bitrix\Highloadblock\HighloadBlockTable;

class HLBlock
{

    public function __construct()
    {
        \CModule::IncludeModule('highloadblock');
    }

    /**
     * @param $name
     * @param string $field
     * @return \Bitrix\Main\Entity\DataManager
     */
    public function getHlEntityByName($name, $field = 'NAME')
    {
        $filter = array($field => $name);
        $hlBlock = HighloadBlockTable::getList(array('filter' => $filter))->fetch();
        $obEntity = HighloadBlockTable::compileEntity($hlBlock);
        return $obEntity->getDataClass();
    }
}