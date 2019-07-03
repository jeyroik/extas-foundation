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
     * @var array
     */
    protected $registeredInterfaces = [];

    /**
     * @var array
     */
    protected $extendedMethodToInterface = [];

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
         */
        $extension = $extRepo->one([
            IExtension::FIELD__SUBJECT => $this->getSubjectForExtension(),
            IExtension::FIELD__METHODS => $name
        ]);

        if (!$extension) {
            throw new \Exception('Unknown method "' . get_class($this) . ':' . $name . '".');
        }

        foreach ($this->getPluginsByStage(IExtendable::STAGE__EXTENDED_METHOD_CALL) as $plugin) {
            $arguments = $plugin($this, $name, $arguments);
        }

        return $extension->runMethod($this, $name, $arguments);
    }

    /**
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

        return $extRepo->one([IExtension::FIELD__INTERFACE => $interface]) ? true : false;
    }

    /**
     * @return string
     */
    abstract protected function getSubjectForExtension(): string;
}
