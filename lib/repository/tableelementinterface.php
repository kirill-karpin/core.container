<?php


namespace Toolbox\Core\Repository;


interface TableElementInterface
{
    public function getId();

    public function setId($id);

    public function toArray();
}