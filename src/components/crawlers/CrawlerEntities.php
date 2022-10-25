<?php
namespace extas\components\crawlers;

class CrawlerEntities extends Crawler
{
    public const FILENAME__PACKAGES = 'extas.json';
    public const FILENAME__APP = 'extas.app.json';

    /**
     * @return array
     * @throws \Exception
     */
    public function __invoke(string $path): array
    {
        return $this->run(static::FILENAME__APP, static::FILENAME__PACKAGES, $path);
    }
}
