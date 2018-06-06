<?php

namespace Toolbox\Core\User\UserGroup;


use Toolbox\Core\Repository\BaseRepository;

class UserGroupRepository extends BaseRepository
{

    public function getEntity()
    {
        return new UserGroupEntityTable();
    }

    public function getUserGroups($userId)
    {
        $result = array();
        $res = $this->entity->getList(array('filter' => array(
            'USER_ID' => $userId
        )));
        while ($item = $res->fetch()) {
            $result[$item['GROUP_ID']] = $item;
        }
        return $result;
    }
}