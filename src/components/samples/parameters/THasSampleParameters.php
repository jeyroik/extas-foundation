<?php
namespace extas\components\samples\parameters;

use extas\interfaces\samples\parameters\IHasSampleParameters;
use extas\interfaces\samples\parameters\ISampleParameter;

/**
 * Trait THasSampleParameters
 *
 * @property $config
 *
 * @package extas\components\samples\parameters
 * @author jeyroik@gmail.com
 */
trait THasSampleParameters
{
    /**
     * @return \Generator <parameter.name>, <parameter>
     */
    public function eachParameter()
    {
        $parameters = $this->config[IHasSampleParameters::FIELD__PARAMETERS] ?? [];
        foreach ($parameters as $name => $parameter) {
            yield $name => new SampleParameter($parameter);
        }
    }

    /**
     * Return parameters list.
     *
     * @return ISampleParameter[] [<parameter.name> => <parameter>]
     */
    public function getParameters()
    {
        $parametersData = $this->config[IHasSampleParameters::FIELD__PARAMETERS] ?? [];
        $parameters = [];

        foreach ($parametersData as $parameterName => $parameterOptions) {
            $parameters[$parameterName] = new SampleParameter($parameterOptions);
        }

        return $parameters;
    }

    /**
     * Return parameters values.
     *
     * @return array [<parameter.name> => <parameter.value>]
     */
    public function getParametersValues()
    {
        $parameters = $this->config[IHasSampleParameters::FIELD__PARAMETERS] ?? [];

        return array_column(
            $parameters,
            ISampleParameter::FIELD__VALUE,
            ISampleParameter::FIELD__NAME
        );
    }

    /**
     * @return array
     */
    public function getParametersNames(): array
    {
        $params = $this->config[static::FIELD__PARAMETERS] ?? [];

        return array_keys($params);
    }

    /**
     * Return a parameter.
     *
     * @param string $parameterName
     * @return ISampleParameter|null
     */
    public function getParameter(string $parameterName): ?ISampleParameter
    {
        $parameters = $this->config[IHasSampleParameters::FIELD__PARAMETERS] ?? [];

        return isset($parameters[$parameterName])
            ? new SampleParameter($parameters[$parameterName])
            : null;
    }

    /**
     * Return a parameter options, throw exception if parameter is missed.
     *
     * @param string $parameterName
     * @return array
     * @throws \Exception
     */
    public function getParameterOptions(string $parameterName): array
    {
        $parameter = $this->getParameter($parameterName);

        if ($parameter) {
            return $parameter->__toArray();
        }

        throw new \Exception('Unknown parameter "' . $parameterName . '"');
    }

    /**
     * @return array
     */
    public function getParametersOptions(): array
    {
        return $this->config[IHasSampleParameters::FIELD__PARAMETERS] ?? [];
    }

    /**
     * Return a parameter value.
     *
     * @param string $parameterName
     * @param mixed $default
     * @return mixed
     */
    public function getParameterValue(string $parameterName, $default = null)
    {
        $parameter = $this->getParameter($parameterName);

        return $parameter ? $parameter->getValue() : $default;
    }

    /**
     * Check if parameter with the name $parameterName is exist.
     *
     * @param string $parameterName
     * @return bool
     */
    public function hasParameter(string $parameterName): bool
    {
        $parameters = $this->config[IHasSampleParameters::FIELD__PARAMETERS] ?? [];

        return isset($parameters[$parameterName]);
    }

    /**
     * Rewrite parameters list.
     *
     * @param ISampleParameter[] $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $parametersData = [];

        foreach ($parameters as $parameter) {
            if ($parameter instanceof ISampleParameter) {
                $parametersData[$parameter->getName()] = $parameter->__toArray();
            }
        }

        $this->config[IHasSampleParameters::FIELD__PARAMETERS] = $parametersData;

        return $this;
    }

    /**
     * @param array $parametersOptions
     * @return $this
     */
    public function setParametersOptions(array $parametersOptions)
    {
        $parametersData = [];

        foreach ($parametersOptions as $parameter) {
            if (is_array($parameter) && isset($parameter[ISampleParameter::FIELD__NAME])) {
                $parametersData[$parameter[ISampleParameter::FIELD__NAME]] = $parameter;
            }
        }

        $this->config[IHasSampleParameters::FIELD__PARAMETERS] = $parametersData;

        return $this;
    }

    /**
     * @param array $parametersValues
     * @return $this
     * @throws \Exception
     */
    public function setParametersValues(array $parametersValues)
    {
        foreach ($parametersValues as $paramName => $paramValue) {
            if ($this->hasParameter($paramName)) {
                $this->setParameterValue($paramName, $paramValue);
            }
        }

        return $this;
    }

    /**
     * Add parameters to a parameters list.
     * Skip parameter if it already exists.
     *
     * @param array $parameters
     * @return $this
     * @throws \Exception
     */
    public function addParameters(array $parameters)
    {
        $parametersData = $this->config[IHasSampleParameters::FIELD__PARAMETERS] ?? [];

        foreach ($parameters as $parameter) {
            if (!$parameter instanceof ISampleParameter) {
                throw new \Exception('Not an extas\\interfaces\\samples\\ISampleParameter');
            }

            if (isset($parametersData[$parameter->getName()])) {
                throw new \Exception('Parameter "' . $parameter->getName() . '" already exists');
            }
            $parametersData[$parameter->getName()] = $parameter->__toArray();
        }

        $this->config[IHasSampleParameters::FIELD__PARAMETERS] = $parametersData;

        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function addParameterByValue(string $name, $value)
    {
        if (!$this->hasParameter($name)) {
            $this->config[static::FIELD__PARAMETERS] = $this->config[static::FIELD__PARAMETERS] ?? [];
            $this->config[static::FIELD__PARAMETERS][$name] = [
                ISampleParameter::FIELD__NAME => $name,
                ISampleParameter::FIELD__VALUE => $value
            ];
        }

        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function addParameterByOptions(array $options)
    {
        $name = $options[ISampleParameter::FIELD__NAME] ?? '';
        if (!$this->hasParameter($name)) {
            $this->config[static::FIELD__PARAMETERS] = $this->config[static::FIELD__PARAMETERS] ?? [];
            $this->config[static::FIELD__PARAMETERS][$name] = $options;
        }

        return $this;
    }

    /**
     * @param array $parametersValues
     * @return $this
     */
    public function addParametersByValues(array $parametersValues)
    {
        foreach ($parametersValues as $parameterName => $parameterValue) {
            $this->addParameterByValue($parameterName, $parameterValue);
        }

        return $this;
    }

    /**
     * @param array $parametersOptions
     * @return $this
     */
    public function addParametersByOptions(array $parametersOptions)
    {
        foreach ($parametersOptions as $parameterOptions) {
            $this->addParameterByOptions($parameterOptions);
        }

        return $this;
    }

    /**
     * Rewrite a parameter.
     * Add parameter if it doesn't exist.
     *
     * @param string $parameterName
     * @param array $options
     * @return $this
     */
    public function setParameter(string $parameterName, array $options)
    {
        $parameters = $this->config[IHasSampleParameters::FIELD__PARAMETERS] ?? [];
        $parameters[$parameterName] = $options;
        $this->config[IHasSampleParameters::FIELD__PARAMETERS] = $parameters;

        return $this;
    }

    /**
     * @param string $parameterName
     * @param mixed $value
     * @return $this
     * @throws \Exception
     */
    public function setParameterValue(string $parameterName, $value)
    {
        $parameter = $this->getParameter($parameterName);
        if ($parameter) {
            $parameter->setValue($value);
            $this->setParameter($parameterName, $parameter->__toArray());
            return $this;
        } else {
            throw new \Exception('Unknown parameter "' . $parameterName . '"');
        }
    }

    /**
     * Update a parameter options.
     * Throw an error if parameter doesn't exist.
     *
     * @param string $parameterName
     * @param array $options
     * @return $this
     * @throws \Exception
     */
    public function updateParameter(string $parameterName, array $options)
    {
        $parameter = $this->getParameterOptions($parameterName);

        foreach ($parameter as $option => $value) {
            if (isset($options[$option])) {
                $parameter[$option] = $options[$option];
            }
        }

        return $this->setParameter($parameterName, $parameter);
    }
}
