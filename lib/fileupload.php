<?php


namespace Toolbox\Core;

use Upload\File;
use Upload\Storage\FileSystem;
use Upload\Validation\Mimetype;
use Upload\Validation\Size;
use Upload\Validation\Extension;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class FileUpload
{
    /**
     * @param string $inputName
     * @param string $uploadDir
     * @param string $type
     * @return void
     */
    public function actionUpload($inputName, $uploadDir = '/upload/photo', $type = '')
    {
        $cFile = new \CFile();
        $errorsUpload = $fileInfo = array();
        $storageDirectory = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
        $storage = new FileSystem($storageDirectory);
        $file = new File($inputName, $storage);
        $fileId = '';

        if ($type == 'doc') {
            $file->addValidations($this->validationDoc());
        } elseif ($type == 'data') {
            $file->addValidations($this->validationData());
        } else {
            $file->addValidations($this->validationImg());
        }

        $name = \CUtil::translit(preg_replace('/\.\w+$/', '', $_FILES[$inputName]['name']), 'ru');
        try {
            $file->setName($name . time());
            $file->upload();
        } catch (\Exception $e) {
            $errorsUpload = $file->getErrors();
        }
        if (empty($errorsUpload)) {
            $fileLink = sprintf('%s/%s', $storageDirectory, $file->getNameWithExtension());
            $fileInfo = $cFile->MakeFileArray($fileLink);
            $uploadDir = str_replace('/upload/', '', $uploadDir);
            $fileId = $cFile->SaveFile($fileInfo, $uploadDir);
        }

        $resizeImg = $cFile->ResizeImageGet($fileId, array('width' => 95, 'height' => 95), BX_RESIZE_IMAGE_PROPORTIONAL, true);
        $resizeImgLong = $cFile->ResizeImageGet($fileId, array('width' => 186, 'height' => 95), BX_RESIZE_IMAGE_PROPORTIONAL, true);
        $fullLink = $cFile->GetPath($fileId);
        $data = array(
            'error' => $errorsUpload,
            'name' => $name,
            'size' => str_replace('.', ',', round($fileInfo['size'] / (1024 * 1024), 2)),
            'size_text' => $cFile->FormatSize($fileInfo['size']),
            'resize_link' => $resizeImg['src'],
            'resize_link_long' => $resizeImgLong['src'],
            'full_link' => $fullLink,
            'id' => (isset($fileId)) ? $fileId : null,
        );

        $GLOBALS['APPLICATION']->RestartBuffer();
        header('Content-Type: application/json');
        exit(json_encode($data));
    }

    /**
     * @param array $fileIds
     * @param array $description
     */
    public function updateDesc($fileIds, $description)
    {
        foreach ($fileIds as $key => $fileId) {
            \CFile::UpdateDesc($fileId, $description[$key]);
        }
    }

    protected function validationDoc()
    {
        return array(
            new Mimetype(array(
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/pdf',
            )),
            new Size('10M')
        );
    }

    protected function validationData()
    {
        return array(
            new Mimetype(array(
                'text/xml',
                'application/json',
                'application/xml',
                'application/zip',
                'application/x-zip-compressed'
            )),
            new Size('50M')
        );
    }


    protected function validationImg()
    {
        return array(
            new Extension(array('png', 'gif', 'jpeg', 'jpg', 'bmp')),
            //TODO: Разобраться, нужна ли такая проверка.
            /*new Mimetype(array(
                'image/png',
                'image/gif',
                'image/jpeg',
                'image/bmp',
            )),*/
            new Size('20M')
        );
    }

    /**
     * @param $link
     * @return bool|int
     */
    public function downloadByLink($link)
    {
        preg_match('/\.\w+$/', $link, $extension);
        $basename = basename(preg_replace('/\.\w+$/', '', $link));
        $path = $_SERVER['DOCUMENT_ROOT'] . '/upload/arrangement/' . $basename . time() . $extension[0];
        if (filter_var($link, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $this->saveRemoteFile($link, $path);
        $fileSize = filesize($path);
        if ($fileSize > 41943040 || $fileSize == 0) {
            return false;
        }

        $info = new \SplFileInfo($path);
        $extension = strtolower($info->getExtension());
        if (
            $extension !== 'png' &&
            $extension !== 'jpg' &&
            $extension !== 'jpeg' &&
            $extension !== 'gif' &&
            $extension !== 'bmp'
        ) {
            return false;
        }

        $cFile = new\CFile();
        $fileInfo = \CFile::MakeFileArray($path);
        $fileId = $cFile->SaveFile($fileInfo, 'arrangement');

        return $fileId;
    }

    /**
     * @param $link
     * @param $path
     */
    protected function saveRemoteFile($link, $path)
    {
        file_put_contents($path, file_get_contents($link));
    }
}