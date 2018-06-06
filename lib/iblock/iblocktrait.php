<?php


namespace Toolbox\Core\Iblock;


trait IBlockTrait
{
    protected $iBlock;

    public function findIBlock($type, $code)
    {
        return \CIBlock::GetList(array(),
            array(
                'CODE' => $code,
                'TYPE' => $type
            ))
            ->Fetch();
    }

    public function getIBlock(){
        if (is_null($this->iBlock)) {
            $class = new \ReflectionClass($this);

            $const = $class->getConstants();

            if ($const['IBLOCK_CODE'] && $const['IBLOCK_TYPE']) {
                $this->iBlock = $this->findIBlock($const['IBLOCK_TYPE'], $const['IBLOCK_CODE']);
            } else {
                throw new \Exception('Not set constant "IBLOCK_TYPE" and "IBLOCK_CODE" for class ' . $class);
            }
        }

        return $this->iBlock;
    }

    public function getIBlockId()
    {
        $iblock = $this->getIBlock();

        return $iblock['ID'];
    }

}
