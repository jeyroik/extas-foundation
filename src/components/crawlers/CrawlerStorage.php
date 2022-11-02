<?php
namespace extas\components\crawlers;

class CrawlerStorage extends Crawler
{
    public const FILENAME__PACKAGES = 'extas.storage.json';
    public const FILENAME__APP = 'extas.app.storage.json';

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
