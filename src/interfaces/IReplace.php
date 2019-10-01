<?php
namespace extas\interfaces;

/**
 * Interface IReplace
 *
 * @package extas\interfaces
 * @author jeyroik@gmail.com
 */
interface IReplace
{
    const METHOD__TO_ARRAY = '__toArray';

    /**
     * @return IReplace
     */
    public static function please(): IReplace;

    /**
     * @param array $values
     *
     * @return $this
     */
    public function apply(array $values): IReplace;

    /**
     * @param string[]|string $templates
     *
     * @return string[]|string
     */
    public function to($templates);
}
