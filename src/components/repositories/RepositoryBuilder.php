<?php
namespace extas\components\repositories;

use extas\components\Item;

class RepositoryBuilder
{
    protected string $savePath = '';
    protected string $templatePath = '';

    public function __construct(string $savePath, string $templatePath)
    {
        $this->savePath = $savePath;
        $this->templatePath = $templatePath;
    }

    public function build(array $driverConfig): void
    {
        $template = file_get_contents($this->templatePath . '/repository_template.txt');
        $repoContent = '';
        $driverClass = $driverConfig['driver'];
        $driver = new $driverClass();

        foreach ($driverConfig['tables'] as $tableName => $tableConfig) {
            $repoContent = str_replace(
                [
                    '{uc_table_name}',
                    'name',
                    'scope',
                    'item_class',
                    'subject',
                    'one-before-hook',
                    'one-after-hook',
                    'all-before-hook',
                    'all-after-hook',
                    'create-before-hook',
                    'create-after-hook',
                    'update-before-hook',
                    'update-after-hook',
                    'delete-before-hook',
                    'delete-after-hook',
                    'drop-before-hook',
                    'drop-after-hook',
                    'driver-get-table'
                ],
                [
                    ucfirst($tableName),
                    $tableName,
                    $tableConfig['scope'] ?? 'extas',
                    $tableConfig['item_class'] ?? Item::class,
                    $tableConfig['subject'] ?? $tableName,
                    $this->createHook('one-before-hook', $tableName, $tableConfig),
                    $this->createHook('one-after-hook', $tableName, $tableConfig),
                    $this->createHook('all-before-hook', $tableName, $tableConfig),
                    $this->createHook('all-after-hook', $tableName, $tableConfig),
                    $this->createHook('create-before-hook', $tableName, $tableConfig),
                    $this->createHook('create-after-hook', $tableName, $tableConfig),
                    $this->createHook('update-before-hook', $tableName, $tableConfig),
                    $this->createHook('update-after-hook', $tableName, $tableConfig),
                    $this->createHook('delete-before-hook', $tableName, $tableConfig),
                    $this->createHook('delete-after-hook', $tableName, $tableConfig),
                    $this->createHook('drop-before-hook', $tableName, $tableConfig),
                    $this->createHook('drop-after-hook', $tableName, $tableConfig),
                    $driver->render($driverConfig, $tableName)
                ],
                $template
            );
            file_put_contents($this->savePath . '/Repository'.ucfirst($tableName).'.php', $repoContent);
        }
    }

    protected function createHook(string $hookName, string $tableName, array $config): string
    {
        if (isset($config['hooks'][$hookName])) {
            return str_replace(
                '{table_name}', 
                $tableName, 
                file_get_contents($this->templatePath . '/hooks_templates/' . $hookName . '.txt')
            );
        }

        return '';
    }
}
