<?php


namespace Toolbox\Core\Repository;


use Bitrix\Main\Error;
use Toolbox\Core\Iblock\IBlockTrait;

abstract class IblockSectionRepository extends BaseRepository
{

    public function createSection(array $data)
    {
        $result = new Result();

        $iblockId = $this->getEntity()
            ->getIblockId();

        $el = new \CIBlockSection();
        $data['IBLOCK_ID'] = $iblockId;

        if ($r = $el->GetList([],
            $data)
            ->Fetch()) {
            $data['ID'] = $r['ID'];

        } else {
            if ($id = $el->Add($data)) {
                $data['ID'] = $id;
            } else {
                $result->addError(new Error($el->LAST_ERROR));
                return $result;
            }
        }
        $result->setId($data['ID']);
        $result->setData([$data]);

        return $result;
    }

    public function getSectionByCode($code)
    {
        $iblockId = $this->getEntity()
            ->getIblockId();

        $el = new \CIBlockSection();

        return $el->GetList([],
            [
                'IBLOCK_ID' => $iblockId,
                'CODE' => $code
            ]
        )
            ->Fetch();
    }

    public function getSectionById($id)
    {
        $iblockId = $this->getEntity()
            ->getIblockId();

        $el = new \CIBlockSection();

        return $el->GetList([],
            [
                'IBLOCK_ID' => $iblockId,
                'ID' => $id,
            ],
        false,
        [
            'UF_*'
        ]
        )
            ->Fetch();
    }
}