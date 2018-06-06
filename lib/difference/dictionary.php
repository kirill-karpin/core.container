<?php

namespace Toolbox\Core\Difference;

use Toolbox\Core\Iblock;
use Toolbox\Core\ContainerTrait;
use Toolbox\Core\Container;
use Toolbox\Core\Repository\BaseRepository;

class Dictionary
{
    protected $savedValues;
    protected $dictFields;
    protected $referenceFields;
    protected $referenceFieldNames;
    protected $enumFields;
    protected $iblockPropFields;
    protected $imageFields;
    protected $fileFields;
    // массив формата *Код свойства, которое надо получить*=>*ID инфоблока*
    protected $attachmentFields = Array(
        'iblock_1' => array(
            'DeviceType' => '2',
            'DeviceBrand' => '3',
            'PROPERTY_DeviceType_VALUE' => '2',
            'PROPERTY_DeviceBrand_VALUE' => '3'
        ),
    );

    /**
     * @param $value
     * @param $fieldName
     * @param $package
     * @return string
     * @throws BaseException
     */
    public function changeNameToReadable($value, $fieldName, $package)
    {
        if (!$value) {
            return '';
        }
        if ($this->savedValues[$package][$fieldName][$value]) {
            return $this->savedValues[$package][$fieldName][$value];
        }

        $referenceField = $this->referenceFields[$package][$fieldName];
        $enumField = $this->enumFields[$package][$fieldName];
        $iblockPropField = $this->iblockPropFields[$package][$fieldName];
        $imageFields = $this->imageFields[$package];
        $fileFields = $this->fileFields[$package];
        $attachmentFields = $this->attachmentFields[$package][$fieldName];

        if ($referenceField) {
            $result = $this->getReferenceName($value, $referenceField);

        } elseif ($enumField) {
            $result = $this->getEnumName($value, $fieldName, $enumField);

        } elseif ($iblockPropField) {
            $result = $this->getIblockPropName($value, $fieldName, $iblockPropField);

        } elseif (in_array($fieldName, $imageFields)) {
            $result = $this->getImage($value);

        } elseif (in_array($fieldName, $fileFields)) {
            $result = $this->getFile($value);

        } elseif ($attachmentFields) {
            $result = $this->getIblockAttachment($value, $fieldName, $attachmentFields);

        } else {
            if (is_array($value)) {
                $result = implode(',<br>', $value);
            } else {
                $result = $value;
            }
        }

        if (!is_array($value)) {
            $this->savedValues[$package][$fieldName][$value] = $result;
        }

        return $result;
    }



    /**
     * @param $value
     * @param $fieldName
     * @param $package
     * @return string
     * @throws BaseException
     */


    /**
     * @param array|int $value
     * @param string $fieldName
     * @param int $iblockId
     * @return string
     */
    protected function getIblockPropName($value, $fieldName, $iblockId)
    {
        $result = '';
        $values = array();
        $props = $this->getIblockProp($fieldName, $iblockId);
        if (is_array($value)) {
            foreach ($value as $id) {
                $values[] = $props[$id]['VALUE'];
            }
            if ($values) {
                $result = implode(',<br>', $values);
            }
        } else {
            $result = $props[$value]['VALUE'];
        }

        return $result;
    }

    /**
     * @param string $xmlId
     * @param string $fieldName
     * @param int $iblockId
     * @return int
     */
    protected function getIblockPropValue($xmlId, $fieldName, $iblockId)
    {
        $props = $this->getIblockProp($fieldName, $iblockId);
        foreach ($props as $item) {
            if ($item['XML_ID'] == $xmlId) {
                return $item['ID'];
            }
        }

        return '';
    }

    /**
     * @param $fieldName
     * @param $iblockId
     * @param string $keyField
     * @return array
     */
    protected function getIblockProp($fieldName, $iblockId, $keyField = 'ID')
    {
        $props = array();
        $cPropEnum = new \CIBlockPropertyEnum();
        $res = $cPropEnum->GetList(
            array('SORT' => 'DESC', 'ID' => 'ASC'),
            array('IBLOCK_ID' => $iblockId, 'ID' => $fieldName)
        );
        while ($item = $res->GetNext()) {
            $props[$item[$keyField]] = $item;
        }
        return $props;
    }

    protected function getIblockAttachment($value, $fieldName, $iblockId)
    {
        $parameters['select'] = Array("ID", "IBLOCK_ID", "NAME");
        $parameters['filter'] = Array('ID'=>$value);
        $res =  \Bitrix\Iblock\ElementTable::getList($parameters)->fetchAll();
        $result=current($res)['NAME'];
        return $result?:false;
    }

}
