<?php
namespace extas\interfaces;

/**
 * Interface IJson
 *
 * Encode:
 * If extas\interfaces\IItem is encoding, __toJson method will be called.
 *
 * Decode:
 * If json contain IJson::MARKER__CLASS, than IJson will try to create an instance of this class.
 *
 * @package extas\interfaces
 * @author jeyroik@gmail.com
 */
interface IJson
{
    public const MARKER__CLASS = '__class__';

    /**
     * @param object|IItem|array $toEncode
     * @return string
     */
    public static function encode($toEncode): string;

    /**
     * @param string $json
     * @param bool $asArray
     * @return object|array|IItem
     */
    public static function decode(string $json, bool $asArray = true);
}
