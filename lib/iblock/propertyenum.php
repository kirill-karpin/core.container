<?
namespace Toolbox\Core\Iblock;

use Bitrix\Iblock\PropertyEnumerationTable as BasePropertyEnumerationTable;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class PropertyEnumTable extends BasePropertyEnumerationTable
{
    /**
     * {@inheritdoc}
     */
    public static function getMap()
    {
        $entityMap = parent::getMap();

        return array_merge($entityMap, array(
            'PROPERTY' => new ReferenceField(
                'PROPERTY',
                'Toolbox\Core\Iblock\Property',
                array('=this.PROPERTY_ID' => 'ref.ID'),
                array('join_type' => 'LEFT')
            )
        ));
    }
}
