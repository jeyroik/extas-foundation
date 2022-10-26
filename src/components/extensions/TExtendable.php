<?php
namespace extas\components\extensions;

use extas\components\exceptions\MissedOrUnknown;
use extas\components\plugins\TPluginAcceptable;
use extas\components\SystemContainer;
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
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws MissedOrUnknown
     */
    public function __call($name, $arguments)
    {
        $extRepo = SystemContainer::getItem(getenv('EXTAS__EXTENSIONS_REPOSITORY') ?: 'extensions');

        /**
         * @var $extension IExtension
         * @var $extensionDispatcher IExtension
         */
        $extension = $extRepo->one([
            IExtension::FIELD__SUBJECT => [$this->getSubjectForExtension(), IExtension::SUBJECT__WILDCARD],
            IExtension::FIELD__METHODS => $name
        ]);

        if (!$extension) {
            if ($fromContainer = $this->getFromContainer($name)) {
                return $fromContainer;
            }
            
            throw new MissedOrUnknown('method "' . get_class($this) . ':' . $name . '".');
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
     * @param string $methodName
     * @return bool
     * @throws \Exception
     */
    public function hasMethod(string $methodName): bool
    {
        if (method_exists($this, $methodName)) {
            return true;
        }

        $extRepo = SystemContainer::getItem(getenv('EXTAS__EXTENSIONS_REPOSITORY') ?: 'extensions');

        return $extRepo->one([
            IExtension::FIELD__METHODS => $methodName,
            IExtension::FIELD__SUBJECT => [$this->getSubjectForExtension(), IExtension::SUBJECT__WILDCARD],
        ]) ? true : false;
    }

    protected function getFromContainer(string $alias)
    {
        try {
            return SystemContainer::getItem($alias);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return string
     */
    abstract protected function getSubjectForExtension(): string;
}
