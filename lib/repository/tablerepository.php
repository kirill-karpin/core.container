<?php


namespace Toolbox\Core\Repository;


use Bitrix\Main\Entity\DeleteResult;
use Bitrix\Main\Entity\EntityError;
use Bitrix\Main\Entity\ScalarField;
use Toolbox\Core\ContainerTrait;


abstract class TableRepository extends BaseRepository
{
    use ContainerTrait;

    /**
     * @param TableElementInterface $element
     * @return Result
     * @throws \Exception
     */
    public function flush(TableElementInterface $element)
    {

        $result = new Result();

        $data = $element->toArray();

        $map = $this->getEntity()
            ->getMap();
        $arFields = [];

        /** @var ScalarField|array $field */
        foreach ($map as $key => $field ) {
            if (is_array($field)) {
                $propKeyArray[] = $key;
            } elseif (is_object($field)){
                $propKeyArray[] = $field->getName();
            }
        }

        foreach ($data as $field => $fieldValue) {
            $propKey = strtoupper(self::toUnderscore($field));
            if (in_array($propKey, $propKeyArray) && $fieldValue) {
                $arFields[$propKey] = $fieldValue;
            }
        }

        if ($element->getId()) {
            $r = $this->getEntity()
                ->update($element->getId(), $arFields);
        } else {
            $r = $this->getEntity()
                ->add($arFields);
            if ($r->isSuccess()) {
                $element->setId($r->getId());
            }
        }

        $result->setId($r->getId())
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

        $data = $this->getEntity()->getList(
            ['filter' => [
                'ID' => $id
            ]]
        )->fetch();

        $r = $this->mapResult([$data]);
        return current($r);
    }
}
