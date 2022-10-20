<?php
namespace extas\components\crawlers;

use Symfony\Component\Finder\Finder;

class CrawlerExtas
{
    public const FILENAME__PACKAGES = 'extas.json';
    public const FILENAME__APP = 'extas.app.json';
    public const FIELD__NAME = 'name';

    /**
     * @return array
     * @throws \Exception
     */
    public function __invoke(string $path): array
    {
        $finder = new Finder();
        $finder->name(static::FILENAME__PACKAGES);
        $extasPackages = [];

        foreach ($finder->in($path)->files() as $file) {
            /**
             * @var $file SplFileInfo
             */
            try {
                $config = json_decode($file->getContents(), true);
            } catch (\Exception $e) {
                continue;
            }
            $currentName = $config[static::FIELD__NAME] ?? $file->getRealPath();
            $extasPackages[$currentName] = $config;
        }

        $appConfig = [];
        $finder->name(static::FILENAME__APP);

        foreach ($finder->in($path)->files() as $file) {
            /**
             * @var $file SplFileInfo
             */
            try {
                $appConfig = json_decode($file->getContents(), true);
                break;
            } catch (\Exception $e) {
                continue;
            }
        }

        return [$appConfig, $extasPackages];
    }
}
