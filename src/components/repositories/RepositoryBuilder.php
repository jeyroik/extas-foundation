<?php
namespace extas\components\repositories;

use extas\components\Item;
use extas\components\SystemContainer;

class RepositoryBuilder
{
    protected string $savePath = '';
    protected string $templatePath = '';
    protected array $placeholders = [
        '{namespace}',
        '{uc_table_name}',
        '{name}',
        '{scope}',
        '{pk}',
        '{item_class}',
        '{subject}',
        '{one-before-hook}',
        '{one-after-hook}',
        '{all-before-hook}',
        '{all-after-hook}',
        '{create-before-hook}',
        '{create-after-hook}',
        '{update-before-hook}',
        '{update-after-hook}',
        '{delete-before-hook}',
        '{delete-after-hook}',
        '{drop-before-hook}',
        '{drop-after-hook}',
        '{driver-class}',
        '{driver-options}'
    ];

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
        $driverOptions = '';
        foreach ($driverConfig['options'] as $name => $value) {
            $driverOptions .= "'".$name."'" . " => '" . $value . "', ";
        }

        foreach ($driverConfig['tables'] as $tableName => $tableConfig) {
            $ns = $tableConfig['namespace'] ?? 'extas\components\repositories';
            $repoContent = str_replace(
                $this->placeholders,
                [
                    $ns,
                    ucfirst($tableName),
                    $tableName,
                    $tableConfig['scope'] ?? 'extas',
                    $tableConfig['pk'] ?? 'id',
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
                    $driverClass,
                    $driverOptions
                ],
                $template
            );
            
            file_put_contents($this->savePath . '/Repository'.ucfirst($tableName) .'.php', $repoContent);
            foreach ($tableConfig['aliases'] as $alias) {
                SystemContainer::saveItem($alias, $ns . '\\Repository' . ucfirst($tableName));
            }
        }

        SystemContainer::reset();
    }

    protected function validateSavePath(): void
    {
        if (!is_dir($this->savePath)) {
            throw new \Exception('Missed save path "' . $this->savePath . '"');
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
