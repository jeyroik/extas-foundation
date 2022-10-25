<?php
namespace extas\components\crawlers;

use Symfony\Component\Finder\Finder;

abstract class Crawler
{
    public const FIELD__NAME = 'name';

    /**
     * @return array
     * @throws \Exception
     */
    protected function run(string $appFilename, string $packagesFilename, string $path): array
    {
        $finder = new Finder();
        $finder->name($packagesFilename);
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
        $finder->name($appFilename);

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
