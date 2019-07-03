<?php
namespace extas\components\stages;

use extas\interfaces\stages\IStage;

/**
 * Class Stage
 *
 * @package extas\components\stages
 * @author jeyroik@gmail.com
 */
class Stage implements IStage
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * Stage constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->setConfig($config);
    }

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
        $input = trim($this->getInput());
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
     * @return array
     */
    public function getOutputAsArray(): array
    {
        $output = trim($this->getOutput());
        return $this->splitArgString($output, static::ARG__TYPE);
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
}
