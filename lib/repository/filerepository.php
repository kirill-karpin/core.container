<?php


namespace Toolbox\Core\Repository;


use Bitrix\Main\Application;
use CFile;

class FileRepository
{
    public function getRequest()
    {
        return Application::getInstance()->getContext()->getRequest();
    }

    public function registerFilesFromRequest()
    {
        $data = [];
        $files = $this->getRequest()->getFileList();

        foreach ($files as $k => $file) {

            if ($file['name'] && ($file['error'] == 0)) {

                $name = $file['name'];
                $arFile = CFile::MakeFileArray($file['tmp_name']);

                $arFile['name'] = $name;
                $imageId = CFile::SaveFile($arFile);

                if ($imageId) {
                    $arElement =  CFile::GetFileArray($imageId);
                    $arElement['name'] = $name;
                    if ($arElement && !empty($arElement)) {
                        $data[$imageId] = ['name'=> $name ,  'FILE' => $arElement, 'KEY' => $k] ;
                    }
                }
            }
        }

        return new Result($data);

    }
}