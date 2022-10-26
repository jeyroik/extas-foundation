<?php
namespace extas\components;

trait TCompareValue
{
    protected function compareValue($source, $compareTo): bool
    {
        $checkers = [
            'isEqualBasic',
            'isEqualIn'
        ];

        $applicable = true;

        foreach ($checkers as $method) {
            $applicable = $this->$method($source, $compareTo);
            if ($applicable) {
                break;
            }
        }

        return $applicable;
    }

    protected function isEqualBasic($source, $compareTo): bool
    {
        if (is_array($source)) {
            return $this->iterateArray($source, $compareTo, 'isEqualBasic');
        }

        return $source == $compareTo;
    }

    protected function isEqualIn($source, $compareTo): bool
    {
        if (is_array($source)) {
            return $this->iterateArray($source, $compareTo, 'isEqualIn');
        }

        if (!is_array($compareTo)) {
            return $this->isEqualBasic($source, $compareTo);
        }

        $equal = false;
        while (!$equal && count($compareTo)) {
            $item = array_shift($compareTo);
            $equal = $this->isEqualBasic($source, $item);
        }

        return $equal;
    }

    protected function iterateArray(array $array, $compareTo, string $method = 'isEqualBasic'): bool
    {
        $equal = false;

        while (!$equal && count($array)) {
            $item = array_shift($array);
            $equal = $this->$method($item, $compareTo);
        }

        return $equal;
    }
}
