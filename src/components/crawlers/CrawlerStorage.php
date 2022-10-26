<?php
namespace extas\components\crawlers;

class CrawlerStorage extends Crawler
{
    public const FILENAME__PACKAGES = 'extas.storage.json';
    public const FILENAME__APP = 'extas.app.storage.json';

    /**
     * @return array
     * @throws \Exception
     */
    public function __invoke(string $path): array
    {
        return $this->run(static::FILENAME__APP, static::FILENAME__PACKAGES, $path);
    }
}
