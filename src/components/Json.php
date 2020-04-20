<?php
namespace extas\components;

use extas\interfaces\IItem;
use extas\interfaces\IJson;

/**
 * Class Json
 * @package extas\components
 * @author jeyroik@gmail.com
 */
class Json implements IJson
{
    /**
     * @param array|IItem|object $toEncode
     * @return string
     */
    public static function encode($toEncode): string
    {
        return ($toEncode instanceof IItem)
            ? $toEncode->__toJson()
            : json_encode($toEncode);
    }

    /**
     * @param string $json
     * @param bool $asArray
     * @return array|object|IItem
     */
    public static function decode(string $json, bool $asArray = false)
    {
        $decoded = json_decode($json, $asArray);
        if (isset($decoded[static::MARKER__CLASS])) {
            $className = $decoded[static::MARKER__CLASS];
            unset($decoded[static::MARKER__CLASS]);
            return new $className($decoded);
        }

        return $decoded;
    }
}
