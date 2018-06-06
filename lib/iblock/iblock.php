<?php


namespace Toolbox\Core\Iblock;

use Bitrix\Iblock\IblockTable as BasePropertyTable;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class IblockTable extends BasePropertyTable
{
    /**
     * {@inheritdoc}
     */
    public static function getMap()
    {
        $entityMap = parent::getMap();

        return array_merge($entityMap, array());
    }
}
