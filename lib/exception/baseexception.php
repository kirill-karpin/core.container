<?php

namespace Toolbox\Core\Exception;

use Bitrix\Main\SystemException;

class BaseException extends SystemException
{
    /**
     * @var array
     */
    private $params;

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
