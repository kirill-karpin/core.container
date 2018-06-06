<?php


namespace Toolbox\Core\Repository;


use Toolbox\Core\Iblock\IBlockEntityInterface;

interface IBlockEntityRepositoryInterface extends EntityRepositoryInterface
{
    /**
     * @return IBlockEntityInterface
     */
    public function getIBlockEntity();
}
