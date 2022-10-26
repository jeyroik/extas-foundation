<?php
namespace extas\components\plugins;

use extas\components\THasConfig;
use extas\components\samples\parameters\THasSampleParameters;
use extas\components\THasClass;
use extas\components\THasHash;
use extas\components\THasId;
use extas\interfaces\plugins\IPlugin;
use extas\components\TAsArray;

/**
 * Class Plugin
 *
 * @property string $class
 * @property string $version
 * @property string $stage
 *
 * @package extas\components\plugins
 * @author jeyroik@gmail.com
 */
class Plugin implements IPlugin
{
    use THasClass;
    use THasId;
    use THasSampleParameters;
    use THasHash;
    use TAsArray;
    use THasConfig;

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->config[static::FIELD__PRIORITY] ?? 0;
    }

    /**
     * @param int $priority
     * @return IPlugin
     */
    public function setPriority(int $priority): IPlugin
    {
        $this->config[static::FIELD__PRIORITY] = $priority;

        return $this;
    }

    /**
     * @param string $stage
     *
     * @return $this
     */
    public function setStage(string $stage): IPlugin
    {
        $this->config[static::FIELD__STAGE] = $stage;

        return $this;
    }

    /**
     * @return string
     */
    public function getStage(): string
    {
        return $this->config[static::FIELD__STAGE] ?? '';
    }

    /**
     * @return string
     */
    public function getInstallOn(): string
    {
        return $this->config[static::FIELD__INSTALL_ON] ?? 'install';
    }
}
