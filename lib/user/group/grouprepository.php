<?php

namespace Toolbox\Core\User\Group;

use Toolbox\Core\Repository\BaseRepository;

class GroupRepository extends BaseRepository
{
    public function getEntity()
    {
        return new GroupEntityTable();
    }

    public function getByCode($groupCode = '')
    {
        if ($groupCode) {
            $r = $this->entity->getList(array(
                'filter' => array(
                    'STRING_ID' => $groupCode
                )
            ));

            return $r->fetch();
        }

        throw  new \Exception('Empty group code.');

    }

    public function save(array $data)
    {
        if ($r = $this->getEntity()
            ->getList(['filter' => ['STRING_ID' => $data["STRING_ID"]]])
            ->fetch()) {
            return $this->update($r['ID'], $data);
        } else {
            return $this->getEntity()
                ->add($data);
        }

    }

}