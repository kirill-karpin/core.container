<?php


namespace Toolbox\Core\User;


use Toolbox\Core\User\Group\GroupRepository;
use Toolbox\Core\User\UserGroup\UserGroupRepository;

class UserService
{
    /**
     * @var UserGroupRepository
     */
    private $userGroupRepository;
    /**
     * @var GroupRepository
     */
    private $groupRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        UserGroupRepository $userGroupRepository,
        GroupRepository $groupRepository,
        UserRepository $userRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    public function getUserGroups($userId)
    {
        return $this->userGroupRepository->getUserGroups($userId);
    }
    public function getUserById($userId = 0)
    {
        /** @var User $user */
        $user = $this->userRepository->getById($userId);

       $groups = $this->getUserGroups($user->getId());

       $user->setGroups($groups);

       return $user;
    }
}