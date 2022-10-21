<?php
namespace extas\interfaces\extensions;

use extas\interfaces\IHasClass;
use extas\interfaces\IHasId;
use extas\interfaces\samples\parameters\IHasSampleParameters;

/**
 * Interface IExtension
 *
 * @package extas\interfaces\extensions
 * @author jeyroik@gmail.com
 */
interface IExtension extends \ArrayAccess, \Iterator, IHasClass, IHasId, IHasSampleParameters
{
    public const SUBJECT = 'extas.extension';

    public const FIELD__INTERFACE = 'interface';
    public const FIELD__SUBJECT = 'subject';
    public const FIELD__METHODS = 'methods';

    public const SUBJECT__WILDCARD = '*';

    /**
     * @param mixed $extendingSubject
     * @param string $methodName
     * @param $args
     *
     * @return mixed
     */
    public function runMethod(&$extendingSubject, $methodName, $args);

    /**
     * @return string
     */
    public function getInterface(): string;

    /**
     * @return string
     */
    public function getSubject(): string;

    /**
     * @return string[]
     */
    public function getMethods(): array;

    /**
     * @param string $interface
     *
     * @return $this
     */
    public function setInterface(string $interface);

    /**
     * @param array $methods
     *
     * @return $this
     */
    public function setMethods(array $methods);

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject(string $subject);
}
