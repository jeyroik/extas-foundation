<?php
namespace extas\interfaces\samples\parameters;

/**
 * Interface IHasSampleParameters
 * 
 * @deprecated Please, use extas\interfaces\parameters\IHaveParams instead
 *
 * @package extas\interfaces\samples\parameters
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IHasSampleParameters
{
    /**
     * Parameters field name
     */
    public const FIELD__PARAMETERS = 'parameters';

    /**
     * @return \Generator <parameter.name>, <parameter>
     */
    public function eachParameter();

    /**
     * Return parameters list.
     *
     * @return ISampleParameter[] [<parameter.name> => <parameter>]
     */
    public function getParameters();

    /**
     * Return parameters values.
     *
     * @return array [<parameter.name> => <parameter.value>]
     */
    public function getParametersValues();

    /**
     * @return array
     */
    public function getParametersNames(): array;

    /**
     * Return a parameter.
     *
     * @param string $parameterName
     * @return ISampleParameter|null
     */
    public function getParameter(string $parameterName): ?ISampleParameter;

    /**
     * Return a parameter options, throw exception if parameter is missed.
     *
     * @param string $parameterName
     * @return array
     * @throws \Exception
     */
    public function getParameterOptions(string $parameterName): array;

    /**
     * @return array
     */
    public function getParametersOptions(): array;

    /**
     * Return a parameter value.
     *
     * @param string $parameterName
     * @param mixed $default
     * @return mixed
     */
    public function getParameterValue(string $parameterName, $default = null);

    /**
     * Check if parameter with the name $parameterName is exist.
     *
     * @param string $parameterName
     * @return bool
     */
    public function hasParameter(string $parameterName): bool;

    /**
     * @param array $options
     * @return $this
     */
    public function addParameterByOptions(array $options);

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function addParameterByValue(string $name, $value);

    /**
     * Rewrite parameters list.
     *
     * @param ISampleParameter[] $parameters
     * @return $this
     */
    public function setParameters(array $parameters);

    /**
     * @param array $parametersOptions
     * @return $this
     */
    public function setParametersOptions(array $parametersOptions);

    /**
     * @param array $parametersValues
     * @return $this
     */
    public function setParametersValues(array $parametersValues);

    /**
     * @param array $parametersValues
     * @return $this
     */
    public function addParametersByValues(array $parametersValues);

    /**
     * @param array $parametersOptions
     * @return $this
     */
    public function addParametersByOptions(array $parametersOptions);

    /**
     * Add parameters to a parameters list.
     * Skip parameter if it already exists.
     *
     * @param ISampleParameter[] $parameters
     * @return $this
     */
    public function addParameters(array $parameters);

    /**
     * Rewrite a parameter.
     * Add parameter if it doesn't exist.
     *
     * @param string $parameterName
     * @param array $options
     * @return $this
     */
    public function setParameter(string $parameterName, array $options);

    /**
     * @param string $parameterName
     * @param mixed $value
     * @return $this
     */
    public function setParameterValue(string $parameterName, $value);

    /**
     * Update a parameter options.
     * Throw an error if parameter doesn't exist.
     *
     * @param string $parameterName
     * @param array $options
     * @return $this
     * @throws \Exception
     */
    public function updateParameter(string $parameterName, array $options);
}
