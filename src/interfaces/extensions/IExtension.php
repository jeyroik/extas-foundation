<?php
namespace extas\interfaces\extensions;

use extas\interfaces\IHasClass;
use extas\interfaces\IItem;

/**
 * Interface IExtension
 *
 * @package extas\interfaces\extensions
 * @author jeyroik@gmail.com
 */
interface IExtension extends IItem, IHasClass
{
    public const SUBJECT = 'extas.extension';

    public const FIELD__INTERFACE = 'interface';
    public const FIELD__SUBJECT = 'subject';
    public const FIELD__METHODS = 'methods';
    public const FIELD__ID = 'id';

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
    public function getId(): string;

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
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id);

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
