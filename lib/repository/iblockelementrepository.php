<?php


namespace Toolbox\Core\Repository;


use Bitrix\Main\Entity\DeleteResult;
use Bitrix\Main\Entity\EntityError;
use Toolbox\Core\ContainerTrait;
use Toolbox\Core\Logger\LoggerTrait;
use ReflectionClass;


abstract class IblockElementRepository extends BaseRepository
{
    use ContainerTrait;
    use LoggerTrait;

    public function listElements($filter = [], $arOrder = [])
    {
        $result = [];

        $filter['IBLOCK_ID'] =  $this->getEntity()
            ->getIBlockId();

        $arSelectFields = [
            '*',
            'PROPERTY_*'
        ];

        $rsElements = \CIBlockElement::GetList($arOrder, $filter, FALSE, FALSE, $arSelectFields);
        while ($i = $rsElements->GetNextElement(1, 0)) {
            $item = $i->GetFields();
            $item['PROPERTIES'] = $i->GetProperties();
            $result[$item['XML_ID']] = $item;
        }

        $result = $this->mapResult($result);
        return $result;
    }

    /**
     * @param IblockElementInterface $element
     * @return Result
     * @throws \Exception
     */
    public function flush(IblockElementInterface $element)
    {
        $result = new Result();

        $data = $element->toArray();

        $props = $this->getEntity()
            ->getProps();


        $map = $this->getEntity()
            ->getMap();


        $arFields = [
            'IBLOCK_ID' => $this->getEntity()
                ->getIBlockId()
        ];

        foreach ($data as $field => $fieldValue) {
            $propKey = strtoupper(self::toUnderscore($field));

            if (array_key_exists($propKey, $map) && $fieldValue) {
                $arFields[$propKey] = $fieldValue;
            } elseif (array_key_exists($propKey, $props)) {

                $p = $props[$propKey];
                /*if ($p['PROPERTY_TYPE'] == 'L') {
                    $fieldValue = $p['LIST']($fieldValue)['ID'];
                }*/

                $arFields['PROPERTY_VALUES'][$p['ID']] = $fieldValue;
            }
        }

        $el = new \CIBlockElement();
        $element->setIblockId($this->getEntity()
            ->getIBlockId());


        if ($element->getId()) {
            if ($el->Update($element->getId(), $arFields)){
                self::log('Element update', [$element->getId(), $arFields]);

            } else {
                self::log('Flush element error', [$el->LAST_ERROR, $arFields]);
            }
        } else {
            if ($id = $el->Add($arFields)) {
                $element->setId($id);
            } else {
                self::error('Error on flush element: ' .$el->LAST_ERROR );
                throw  new \Exception($el->LAST_ERROR);
            }
        }

        $result->setId($element->getId())
            ->setData([$element]);

        return $result;
    }


    /**
     * @param  int $id
     * @return DeleteResult
     */
    public function delete($id)
    {
        $iblockElement = new \CIBlockElement();
        $result = new DeleteResult();

        $iblockElementResult = $iblockElement->delete($id);
        if ($iblockElementResult !== true) {
            $result->addError(new EntityError($iblockElement->LAST_ERROR));
        } else {
            $result->setData(array(
                'ID' => $id,
            ));
        }

        return $result;
    }

    public function getById($id)
    {
        $arOrder = ["SORT" => "ASC"];
        $arFilter = [
            'IBLOCK_ID' => $this->getEntity()
                ->getIBlockId(),
            'ID' => $id
        ];
        $arSelectFields = [
            "*"
        ];
        $rsElements = \CIBlockElement::GetList($arOrder,
            $arFilter,
            FALSE,
            FALSE,
            $arSelectFields
        );

        if ($arElement = $rsElements->GetNextElement(1, 0)) {
            $element = $arElement->GetFields();
            $props = $arElement->GetProperties();
            $element['PROPERTIES'] = $props;

            return  current($this->mapResult([$element]));
        }

        return null;
    }
}
