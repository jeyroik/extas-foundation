<?php
namespace extas\components\extensions;

use extas\components\plugins\TPluginAcceptable;
use extas\components\SystemContainer;
use extas\interfaces\extensions\IExtendable;
use extas\interfaces\extensions\IExtension;
use extas\interfaces\extensions\IExtensionRepository;

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
        /**
         * @var $extRepo IExtensionRepository
         */
        $extRepo = SystemContainer::getItem(IExtensionRepository::class);

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
            IExtension::FIELD__METHODS => $name
        ]);

        return $extensionDispatcher->runMethod($this, $name, $arguments);
    }

    /**
     * @deprecated
     * @param string $interface
     *
     * @return bool
     */
    public function isImplementsInterface(string $interface): bool
    {
        /**
         * @var $extRepo IExtensionRepository
         */
        $extRepo = SystemContainer::getItem(IExtensionRepository::class);

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
