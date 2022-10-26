<?php
namespace extas\components\repositories;

trait TLimitWithOffset
{
    protected function limit(array $items, $limit): array
    {
        if (!$limit || $limit >= count($items)) {
            return $items;
        }

        $limited = [];

        while (count($limited) < $limit) {
            $limited[] = array_shift($items);
        }

        return $limited;
    }

    protected function offset(array $items, int $offset)
    {
        $withOffset = [];

        foreach ($items as $index => $item) {
            if ($index < $offset) {
                continue;
            }
            $withOffset[] = $item;
        }

        return $withOffset;
    }
}
