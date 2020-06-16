<?php
namespace extas\components\samples;

use extas\interfaces\IHasName;
use extas\interfaces\samples\IHasSample;
use extas\interfaces\samples\ISample;

/**
 * Trait THasSample
 *
 * @property $config
 *
 * @package extas\components\samples
 * @author jeyroik <jeyroik@gmail.com>
 */
trait THasSample
{
    /**
     * @return string
     */
    public function getSampleName(): string
    {
        return $this->config[IHasSample::FIELD__SAMPLE_NAME] ?? '';
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function setSampleName(string $name)
    {
        $this->config[IHasSample::FIELD__SAMPLE_NAME] = $name;

        return $this;
    }

    /**
     * @param ISample $sample
     * @param string $name
     * @return $this
     */
    public function buildFromSample(ISample $sample, string $name = '@sample(uuid6)')
    {
        $sampleData = $sample->__toArray();
        $sampleData[IHasSample::FIELD__SAMPLE_NAME] = $sample->getName();
        $name && ($sampleData[IHasName::FIELD__NAME] = $name);
        $this->config = $sampleData;

        return $this;
    }
}
