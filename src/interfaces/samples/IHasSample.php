<?php
namespace extas\interfaces\samples;

/**
 * Interface IHasSample
 *
 * @package extas\interfaces\samples
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IHasSample
{
    public const FIELD__SAMPLE_NAME = 'sample_name';

    /**
     * @return string
     */
    public function getSampleName(): string;

    /**
     * @param string $name
     * @return mixed
     */
    public function setSampleName(string $name);

    /**
     * @param ISample $sample
     * @param string $name
     * @return $this
     */
    public function buildFromSample(ISample $sample, string $name = '@sample(uuid6)');
}
