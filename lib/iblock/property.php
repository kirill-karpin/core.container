<?
namespace Toolbox\Core\Iblock;

use Bitrix\Iblock\PropertyTable as BasePropertyTable;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class PropertyTable extends BasePropertyTable
{
    /**
     * {@inheritdoc}
     */
    public static function getMap()
    {
        $entityMap = parent::getMap();

        return array_merge($entityMap, array(
            'IBLOCK' => new ReferenceField(
                'IBLOCK',
                'Toolbox\Core\Iblock\IblockTable',
                array('=this.IBLOCK_ID' => 'ref.ID'),
                array('join_type' => 'LEFT')
            )
        ));
    }
}
