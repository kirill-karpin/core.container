<?php


namespace Toolbox\Core\Iblock;


use CUserTypeEntity;

class UserField
{
    public function addSection($IBlockId, $data = [])
    {
        /**
         * Добавление пользовательского свойства
         */
        $oUserTypeEntity = new CUserTypeEntity();

        $aUserFields = array(
            'ENTITY_ID' => 'IBLOCK_' . $IBlockId . '_SECTION',
            'FIELD_NAME' => $data['FIELD_NAME'],
            'USER_TYPE_ID' => $data['USER_TYPE_ID'],
            'XML_ID' => $data['XML_ID'],
            'SORT' => $data['SORT'] ?: 500,
            'MULTIPLE' => $data['MULTIPLE'] ?: 'N',
            'MANDATORY' => $data['MANDATORY'] ?: 'N',
            'SHOW_FILTER' => $data['SHOW_FILTER'] ?: 'N',
            'SHOW_IN_LIST' => $data['SHOW_IN_LIST'] ?: '',
            'EDIT_IN_LIST' => $data['EDIT_IN_LIST'] ?: '',
            'IS_SEARCHABLE' => $data['IS_SEARCHABLE'] ?: 'N',
            'SETTINGS' => $data['SETTINGS'],
            'EDIT_FORM_LABEL' => array(
                'ru' => $data['EDIT_FORM_LABEL']['ru'] ?: 'Пользовательское свойство',
                'en' => 'User field',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru' => $data['LIST_COLUMN_LABEL']['ru'] ?: 'Пользовательское свойство',
                'en' => 'User field',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru' => $data['LIST_FILTER_LABEL']['ru'] ?: 'Пользовательское свойство',
                'en' => 'User field',
            ),
            'ERROR_MESSAGE' => array(
                'ru' => 'Ошибка при заполнении пользовательского свойства',
                'en' => 'An error in completing the user field',
            ),
            'HELP_MESSAGE' => array(
                'ru' => '',
                'en' => '',
            ),
        );
        if ($iUserFieldId = $oUserTypeEntity->Add($aUserFields)){

            if ($data['VALUES']) {
                $obEnum = new \CUserFieldEnum();
                $obEnum->SetEnumValues($iUserFieldId, $data['VALUES']);
            }

            return $iUserFieldId;
        }  else {
            throw new \Exception('UserField not created ');
        }

    }

    public function isExistIBlock($iblockId, $fieldName)
    {
        if ($dbr = CUserTypeEntity::GetList([], [
            'FIELD_NAME' => $fieldName,
            'ENTITY_ID' => 'IBLOCK_' . $iblockId . '_SECTION'
        ])->Fetch()){

            return $dbr;
        }

        return false;
    }
}
