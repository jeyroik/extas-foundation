<?php
namespace extas\components\extensions;

use extas\components\plugins\TPluginAcceptable;
use extas\interfaces\extensions\IExtension;

/**
 * Trait TExtendable
 *
 * @package extas\components\extensions
 * @author jeyroik@gmail.com
 */
trait TExtendable
{
    use TPluginAcceptable;

    /**
     * @deprecated
     * @var array
     */
    protected array $registeredInterfaces = [];

    /**
     * @deprecated
     * @var array
     */
    protected array $extendedMethodToInterface = [];

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed|null
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        $extRepo = new ExtensionRepository();

        /**
         * @var $extension IExtension
         * @var $extensionDispatcher IExtension
         */
        $extension = $extRepo->one([
            IExtension::FIELD__SUBJECT => [$this->getSubjectForExtension(), IExtension::SUBJECT__WILDCARD],
            IExtension::FIELD__METHODS => $name
        ]);

        if (!$extension) {
            throw new \Exception('Unknown method "' . get_class($this) . ':' . $name . '".');
        }

        $extensionDispatcher = $extension->buildClassWithParameters([
            IExtension::FIELD__CLASS => $extension->getClass(),
            IExtension::FIELD__INTERFACE => $extension->getInterface(),
            IExtension::FIELD__SUBJECT => $this->getSubjectForExtension(),
            IExtension::FIELD__METHODS => $name,
            IExtension::FIELD__PARAMETERS => $extension->getParametersValues()
        ]);

        return $extensionDispatcher->runMethod($this, $name, $arguments);
    }

    /**
     * @param string $interface
     * @return bool
     * @throws \Exception
     */
    public function isImplementsInterface(string $interface): bool
    {
        $extRepo = new ExtensionRepository();

        return $extRepo->one([
            IExtension::FIELD__INTERFACE => $interface,
            IExtension::FIELD__SUBJECT => [$this->getSubjectForExtension(), IExtension::SUBJECT__WILDCARD],
        ]) ? true : false;
    }

    /**
     * @param string $methodName
     * @return bool
     */
    public function hasMethod(string $methodName): bool
    {
        if (method_exists($this, $methodName)) {
            return true;
        }

        /**
         * @var $extRepo IExtensionRepository
         */
        $extRepo = SystemContainer::getItem(IExtensionRepository::class);

        return $extRepo->one([
            IExtension::FIELD__METHODS => $methodName,
            IExtension::FIELD__SUBJECT => [$this->getSubjectForExtension(), IExtension::SUBJECT__WILDCARD],
        ]) ? true : false;
    }

    /**
     * @return string
     */
    abstract protected function getSubjectForExtension(): string;
}
