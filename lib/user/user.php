<?php


namespace Toolbox\Core\User;


class User
{
    private $id;
    private $name;
    private $secondName;
    private $lastName;
    private $login;
    private $email;
    private $active;
    private $personalProfession;
    private $personalPhone;
    private $personalMobile;
    private $personalPhoto;
    private $workPosition;
    private $groups;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param mixed $groups
     * @return User
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
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
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSecondName()
    {
        return $this->secondName;
    }

    /**
     * @param mixed $secondName
     * @return User
     */
    public function setSecondName($secondName)
    {
        $this->secondName = $secondName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }



    public function getFullName()
    {
        return implode(' ', [$this->getName(), $this->getLastName()]);
    }

    /**
     * @return mixed
     */
    public function getWorkPosition()
    {
        return $this->workPosition;
    }

    /**
     * @param mixed $workPosition
     * @return User
     */
    public function setWorkPosition($workPosition)
    {
        $this->workPosition = $workPosition;
        return $this;
    }

}