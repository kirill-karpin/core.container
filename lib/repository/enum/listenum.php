<?php


namespace Toolbox\Core\Repository\Enum;


use Toolbox\Core\Iblock\IBlockTrait;

class ListEnum extends AbstractEnum
{
    use IBlockTrait;
    protected $data = [];
    private $propertyId;
    private $iblockId;
    private $code;

    /**
     * ListEnum constructor.
     * @param $propertyId
     * @param array $data
     */
    public function __construct($propertyId = null, array $data = null)
    {
        $this->data = $data;
        $this->propertyId = $propertyId;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->setData()->data;
    }

    public function lazyInit()
    {
        $reflector = new \ReflectionClass($this);
        if($const = $reflector->getConstant('CODE')){
            $this->iblockId = $this->getIBlockId();
            $this->code = $const;
        } else {
            throw  new \Exception('Not set constant CODE in class '. self::class );
        }
    }



    public function setData()
    {
        if (is_null($this->data)) {

            if (is_null($this->propertyId) ){
                $this->lazyInit();
                $filter['IBLOCK_ID'] = $this->iblockId;
                $filter['PROPERTY_ID'] = $this->code;

            } else {
                $filter = [
                    'PROPERTY_ID' => $this->propertyId
                ];
            }

            $result = \CIBlockPropertyEnum::GetList(['SORT' => 'ASC'],
                $filter
            );

            while ($r = $result->Fetch()) {

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