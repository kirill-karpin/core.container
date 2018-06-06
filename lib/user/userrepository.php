<?php


namespace Toolbox\Core\User;


use Toolbox\Core\Repository\TableRepository;



class UserRepository extends TableRepository
{
    public function getEntity()
    {
        return new UserEntityTable();
    }
}