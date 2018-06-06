<?php


namespace Toolbox\Core\Repository;


interface IblockElementInterface
{
    public function getId();

    public function setId($id);

    public function setIblockId($iblockId);

    public function getIblockId();

    public function toArray();
}