<?
namespace Toolbox\Core\Iblock;

use Toolbox\Core\Repository\BaseRepository;

class PropertyEnumRepository extends BaseRepository
{
    /**
     * @param  string    $iblockCode
     * @param  string $code
     * @param  string $group
     * @return array
     */
    public function getEnumValues($iblockCode, $code, $group = 'XML_ID')
    {
        $filter = array(
            'PROPERTY.IBLOCK.CODE' => $iblockCode,
            'PROPERTY.CODE' => $code,
        );

        $queryBuilder = $this->entity->query();
        $record = $queryBuilder->setSelect(array('*'))
            ->setFilter($filter)
            ->exec();

        $result = array();
        foreach ($record->fetchAll() as $enum) {
            $result[$enum[$group]] = $enum;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return new PropertyEnumTable();
    }
}
