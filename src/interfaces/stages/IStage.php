<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;

/**
 * Interface IStage
 *
 * @package extas\interfaces\stages
 * @author jeyroik@gmail.com
 */
interface IStage extends IItem
{
    public const SUBJECT = 'extas.stage';

    public const FIELD__NAME = 'name';
    public const FIELD__DESCRIPTION = 'description';
    public const FIELD__INPUT = 'input';
    public const FIELD__OUTPUT = 'output';
    public const FIELD__HAS_PLUGINS = 'has_plugins';

    public const ARG__TYPE = 'type';
    public const ARG__NAME = 'arg';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return string
     */
    public function getInput(): string;

    /**
     * @return string
     */
    public function getOutput(): string;

    /**
     * @return array
     */
    public function getInputAsArray(): array;

    /**
     * @return array
     */
    public function getOutputAsArray(): array;

    /**
     * @return bool
     */
    public function hasPlugins(): bool;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name);

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description);

    /**
     * @param string $input
     *
     * @return $this
     */
    public function setInput(string $input);

    /**
     * @param string $output
     *
     * @return $this
     */
    public function setOutput(string $output);

    /**
     * @param $has bool
     *
     * @return $this
     */
    public function setHasPlugins(bool $has);
}
