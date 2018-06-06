<?php


namespace Toolbox\Core\Repository;


use Toolbox\Core\Util\Lang;

class Result extends \Bitrix\Main\Result implements \Iterator
{
    use Lang;

    private $id;
    /**
     * Result constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct();
        $this->setData($data);
    }

    public function rewind()
    {
        return reset($this->data);
    }

    public function current()
    {
        return current($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function next()
    {
        return next($this->data);
    }

    public function valid()
    {
        return key($this->data) !== null;
    }

    public function count()
    {
        return count($this->data);
    }

    public function hasNext()
    {
        $current = $this->current();

        $this->next();
        if ($this->valid()) {
            return false;
        }

        return $current;
    }

    public function toArray($upper = false)
    {
        $result = [];
        foreach ($this->data as $k => $v) {

            if (is_array($v)){
                $result[] = $v;
            } elseif (is_object($v)) {
                $result[] = $v->toArraySensitive($upper);
            }
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getById($id)
    {
        return current(array_filter($this->data, function($v) use ($id){
            return $v->getId() == $id;
        }));
    }
}