<?php


namespace Toolbox\Core\Util;


trait ArrayTrait
{
    public function toArray()
    {
        return get_object_vars($this);
    }

    public function toArraySensitive($upper = false)
    {
        $result = [];
        $data = $this->toArray();
        foreach ($data as $k => $v) {

            if (is_object($v) && method_exists($v, 'toArray') ) {
                $v = $v->toArray(1);
            }

            if ($upper) {
                $result[strtoupper(self::toUnderscore($k))] =  $v;
            } else {
                $result[self::toUnderscore($k)] =  $v;
            }
        }

        return $result;

    }
}