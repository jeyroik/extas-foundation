<?php
namespace extas\components\crawlers;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class CrawlerPHP
{
    protected string $pattern = '';
    protected string $path = '';

    public function __construct(string $pattern, string $pathToSearch)
    {
        $this->pattern = $pattern;
        $this->path = $pathToSearch;
    }

    public function run(string $pathToSave): void
    {
        $finder = new Finder();
        $finder->name($this->pattern . '.php');

        foreach ($finder->in($this->path)->files() as $file) {
            $config = include $file->getRealPath();
            $parts = explode(DIRECTORY_SEPARATOR, $file->getRealPath());

            $packageDir = $pathToSave . DIRECTORY_SEPARATOR .$parts[count($parts)-2];

            if (!is_dir($packageDir)) {
                mkdir($packageDir, 0755);
            }
            file_put_contents($packageDir . '/' . $this->getFileName($file), json_encode($config, JSON_PRETTY_PRINT));
        }
    }

    protected function getFileName(SplFileInfo $file): string
    {
        return str_replace('.php', '.json', basename($file->getRealPath()));
    }
}
