<?php
namespace extas\components\crawlers;

class CrawlerEntities extends Crawler
{
    public const FILENAME__PACKAGES = 'extas.json';
    public const FILENAME__APP = 'extas.app.json';

    protected string $appFilename = self::FILENAME__APP;
    protected string $packagesFilename = self::FILENAME__PACKAGES;

    /**
     * @return array
     * @throws \Exception
     */
    public function __invoke(string $path): array
    {
        return $this->run($this->appFilename, $this->packagesFilename, $path);
    }
}
