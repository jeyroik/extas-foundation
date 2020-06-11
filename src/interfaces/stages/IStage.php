<?php
namespace extas\interfaces\stages;

use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IItem;

/**
 * Interface IStage
 *
 * @package extas\interfaces\stages
 * @author jeyroik@gmail.com
 */
interface IStage extends IItem, IHasName, IHasDescription
{
    public const SUBJECT = 'extas.stage';

    public const FIELD__INPUT = 'input';
    public const FIELD__OUTPUT = 'output';

    public const ARG__TYPE = 'type';
    public const ARG__NAME = 'arg';

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
}
