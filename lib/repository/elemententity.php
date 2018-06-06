<?php


namespace Toolbox\Core\Repository;


use Bitrix\Iblock\ElementTable;
use Toolbox\Core\Iblock\IBlockTrait;
use Toolbox\Core\Logger\LoggerTrait;
use Toolbox\Core\Repository\Enum\ListEnum;
use Toolbox\Core\Repository\Enum\NotImpMethodException;

class ElementEntity extends ElementTable implements IblockElementInterface
{
    use IBlockTrait;
    use LoggerTrait;

    protected $props;
    protected $id;
    protected $iblockId;

    public function getProps()
    {
        if (is_null($this->props)) {
            $iblockId = $this->getIBlockId();

            self::log('Element iblock id '. $iblockId);

            $ib = new \CIBlockProperty();
            $dbr = $ib->GetList([], ['IBLOCK_ID' => $iblockId]);
            while ($r = $dbr->Fetch()) {
                if ($r['PROPERTY_TYPE'] == 'L') {
                    $r['LIST'] = new ListEnum($r['ID']);
                }
                $this->props[$r['CODE']] = $r;

            }
        }

        return $this->props;
    }

    /**
     * @return mixed
     * @throws NotImpMethodException
     */
    public function getId()
    {
        throw new NotImpMethodException();
    }

    /**
     * @return mixed
     * @throws NotImpMethodException
     */
    public function toArray()
    {
        throw new NotImpMethodException();
    }

    /**
     * @param mixed $iblockId
     */
    public function setIblockId($iblockId)
    {
        $this->iblockId = $iblockId;
    }


    public function setId($id)
    {
        throw new NotImpMethodException();
    }


}