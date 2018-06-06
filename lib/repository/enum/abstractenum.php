<?php


namespace Toolbox\Core\Repository\Enum;


class AbstractEnum implements EnumInterface
{
    protected $data = [];
    private $propertyId;

    /**
     * ListEnum constructor.
     * @param $propertyId
     * @param array $data
     */
    public function __construct($propertyId, array $data = null)
    {
        $this->data = $data;
        $this->propertyId = $propertyId;
    }

    public function setData()
    {
        throw new NotImpMethodException();
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
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

    public function getByValue($value)
    {
        $this->getData();
        return current( array_filter($this->data,
            function ($v) use ($value) {
                return $v['VALUE'] == $value;
            }));
    }

    public function __invoke($code)
    {

        return $this->getByXmlId($code);
    }
}