<?php


namespace Toolbox\Core\Repository\Enum;


use CUserFieldEnum;

class UserFieldEnum extends AbstractEnum
{
    protected $data;
    private $propertyId;
    private $code;

    /**
     * ListEnum constructor.
     */
    public function __construct()
    {
        $reflector = new \ReflectionClass($this);
        $consts = $reflector->getConstants();

        if ($consts['CODE']) {
            $this->code = $consts['CODE'];
        } else {
            throw new EnumException(self::class . ' no code set');
        }

    }

    /**
     * @return array
     */
    public function getData()
    {
        $this->setData();
        return $this->data;
    }



    public function setData()
    {
        if (is_null($this->data)) {
            $obEnum = new CUserFieldEnum;
            $rsEnum = $obEnum->GetList(array(), array('USER_FIELD_NAME' => $this->code));
            while($r = $rsEnum->GetNext(1,0)){
                $this->data[] = $r;
            }
        }
        return $this;
    }


    public function getById($id)
    {
        $this->getData();

        return current(array_filter($this->data,
            function ($v) use ($id) {
                return $v['ID'] == $id;
            }));
    }

    public function getByCode($code)
    {
        $this->getData();

        return current( array_filter($this->data,
            function ($v) use ($code) {
                return $v['CODE'] == $code;
            }));
    }

    public function getByXmlId($code)
    {
        $this->getData();
        return current( array_filter($this->data,
            function ($v) use ($code) {
                return $v['XML_ID'] == $code;
            }));
    }

    public function __invoke($code)
    {

        return $this->getByXmlId($code);
    }
}