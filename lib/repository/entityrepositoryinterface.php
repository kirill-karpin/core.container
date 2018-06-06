<?php


namespace Toolbox\Core\Repository;


use Bitrix\Main\Entity\DataManager;

interface EntityRepositoryInterface
{

    /**
     * @return DataManager
     */
    public function getEntity();
}
