<?php


namespace Toolbox\Core\Repository;

use Bitrix\Main\Type\DateTime;

abstract class ElementModel implements IblockElementInterface
{
    protected $id;
    protected $iblockId;
    protected $name;
    protected $active;
    protected $sort;
    /** @var \Bitrix\Main\Type\DateTime */
    protected $dateCreate;
    /** @var \Bitrix\Main\Type\DateTime */
    protected $timestampX;
    protected $iblockSectionId;
    protected $createdBy;
    protected $detailPicture;
    protected $previewPicture;
    protected $previewText;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return ElementModel
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIblockId()
    {
        return $this->iblockId;
    }

    /**
     * @param mixed $iblockId
     * @return ElementModel
     */
    public function setIblockId($iblockId)
    {
        $this->iblockId = $iblockId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return ElementModel
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * @param DateTime $dateCreate
     * @return ElementModel
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimestampX()
    {
        return $this->timestampX;
    }

    /**
     * @param mixed $timestampX
     * @return ElementModel
     */
    public function setTimestampX($timestampX)
    {
        $this->timestampX = $timestampX;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIblockSectionId()
    {
        return $this->iblockSectionId;
    }

    /**
     * @param mixed $iblockSectionId
     * @return ElementModel
     */
    public function setIblockSectionId($iblockSectionId)
    {
        $this->iblockSectionId = $iblockSectionId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     * @return ElementModel
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetailPicture()
    {
        return $this->detailPicture;
    }

    /**
     * @param mixed $detailPicture
     * @return ElementModel
     */
    public function setDetailPicture($detailPicture)
    {
        $this->detailPicture = $detailPicture;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreviewPicture()
    {
        return $this->previewPicture;
    }

    /**
     * @param mixed $previewPicture
     * @return ElementModel
     */
    public function setPreviewPicture($previewPicture)
    {
        $this->previewPicture = $previewPicture;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreviewText()
    {
        return $this->previewText;
    }

    /**
     * @param mixed $previewText
     * @return ElementModel
     */
    public function setPreviewText($previewText)
    {
        $this->previewText = $previewText;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     * @return ElementModel
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    public function isActive()
    {
        return ($this->active == 'Y');
    }

    public function deactivate()
    {
        $this->active = 'N';
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     * @return ElementModel
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
        return $this;
    }

}