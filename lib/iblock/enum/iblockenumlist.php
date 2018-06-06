<?php


namespace Toolbox\Core\Iblock\Enum;


use Toolbox\Core\Iblock\IBlockTrait;
use ReflectionClass;

abstract class IBlockEnumList
{
    use IBlockTrait;

    private $data;
    protected $propertyCode;
    protected $iBlockCode;
    protected $iBlockType;

    const CODE_NAME = 'IBLOCK_CODE';
    const TYPE_NAME = 'IBLOCK_TYPE';
    const PROP_CODE_NAME = 'PROP_CODE';

    /**
     * IBlockEnumList constructor.
     * @param $propertyCode
     * @param $iBlockCode
     * @param $iBlockType
     * @throws \Exception
     */
    public function __construct($propertyCode = null, $iBlockCode = null, $iBlockType = '')
    {
        if (($propertyCode) && ($iBlockCode)) {

            $this->propertyCode = $propertyCode;
            $this->iBlockCode = $iBlockCode;
            $this->iBlockType = $iBlockType;

        } else {
            $p = get_class($this);
            $class = new ReflectionClass($p);
            $const = $class->getConstants();

            if ($const[self::CODE_NAME]) {
                $this->iBlockCode = $const[self::CODE_NAME];
            } else {
                throw new \Exception('Not set constant ' . self::CODE_NAME . ' for class ' . $class);
            }

            if ($const[self::TYPE_NAME]) {
                $this->iBlockCode = $const[self::TYPE_NAME];
            } else {
                throw new \Exception('Not set constant ' . self::TYPE_NAME . ' for class ' . $class);
            }

            if ($const[self::PROP_CODE_NAME]) {
                $this->propertyCode = $const[self::PROP_CODE_NAME];
            } else {
                throw new \Exception('Not set constant ' . self::PROP_CODE_NAME . ' for class ' . $class);
            }
        }


    }


    public function resetData()
    {
        $this->data = null;
    }


    public function __invoke($code)
    {
        $data = $this->getData();

        return $data[$code]['ID'];
    }

    public function getByCode($code)
    {
        $data = $this->getData();

        return $data[$code];
    }

    public function getByValue($code)
    {
        $data = $this->getData();

        foreach ($data as $i){
            if ($i['VALUE'] == $code){
                return $i;
            }
        }

        return false;
    }

    public function getData()
    {

        $this->setData();

        return $this->data;
    }



    private function setData()
    {
        if (is_null($this->data)) {

            $iblock = self::findIBlock($this->iBlockType, $this->iBlockCode);

            $result = \CIBlockPropertyEnum::GetList(array('SORT' => 'ASC'),
                array(
                    'IBLOCK_ID' => $iblock['ID'],
                    'CODE' => $this->propertyCode
                ));

            while ($r = $result->Fetch()) {
                $this->data[$r['XML_ID']] = $r;
            }
        }
    }

    public function getById($id)
    {
        $data = $this->getData();
        $key = array_search($id, array_column(array_values($data), 'ID'));

        return array_values($data)[$key];
    }
}
