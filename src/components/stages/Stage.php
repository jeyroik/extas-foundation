<?php
namespace extas\components\stages;

use extas\components\Item;
use extas\components\THasDescription;
use extas\components\THasName;
use extas\interfaces\stages\IStage;

/**
 * Class Stage
 *
 * @package extas\components\stages
 * @author jeyroik@gmail.com
 */
class Stage extends Item implements IStage
{
    use THasName;
    use THasDescription;

    protected bool $isAllowInitStage = false;
    protected bool $isAllowAfterStage = false;
    protected bool $isAllowCreatedStage = false;
    protected bool $isAllowToArrayStage = false;
    protected bool $isAllowToIntStage = false;
    protected bool $isAllowToStringStage = false;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->config[static::FIELD__NAME] ?? '';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->config[static::FIELD__DESCRIPTION] ?? '';
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->config[static::FIELD__NAME] = $name;

        return $this;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->config[static::FIELD__DESCRIPTION] = $description;

        return $this;
    }

    /**
     * @param string $input
     *
     * @return $this
     */
    public function setInput(string $input)
    {
        $this->config[static::FIELD__INPUT] = $input;

        return $this;
    }

    /**
     * @param string $output
     *
     * @return $this
     */
    public function setOutput(string $output)
    {
        $this->config[static::FIELD__OUTPUT] = $output;

        return $this;
    }

    /**
     * @return string
     */
    public function getInput(): string
    {
        return $this->config[static::FIELD__INPUT] ?? '';
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->config[static::FIELD__OUTPUT] ?? '';
    }

    /**
     * @return array
     */
    public function getInputAsArray(): array
    {
        return $this->getAsArray($this->getInput());
    }

    /**
     * @return array
     */
    public function getOutputAsArray(): array
    {
        return $this->getAsArray($this->getOutput());
    }

    /**
     * @param string $stringToParse
     * @return array
     */
    protected function getAsArray(string $stringToParse): array
    {
        $input = trim($stringToParse);
        $args = [];

        if (strpos($input, ',') !== false) {
            $multiple = explode(',', $input);

            foreach ($multiple as $argString) {
                $args[] = $this->splitArgString($argString);
            }
        } else {
            $args[] = $this->splitArgString($input);
        }

        return $args;
    }

    /**
     * @param $config
     *
     * @return $this
     */
    protected function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param $argString
     * @param string $onPart
     *
     * @return array
     */
    protected function splitArgString($argString, $onPart = self::ARG__NAME)
    {
        if (strpos($argString, ' ') !== false) {
            list($type, $argName) = explode(' ', $argString);
        } else {
            $byParts = [
                static::ARG__NAME => static::ARG__TYPE,
                static::ARG__TYPE => static::ARG__NAME
            ];

            return [
                $onPart => $argString,
                $byParts[$onPart] => ''
            ];
        }

        return [
            static::ARG__TYPE => $type,
            static::ARG__NAME => $argName
        ];
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
