<?php
namespace extas\components\repositories;

use extas\components\THasConfig;
use extas\components\Item;
use extas\components\SystemContainer;
use extas\components\THasOutput;
use extas\interfaces\repositories\IRepositoryBuilder;

class RepositoryBuilder implements IRepositoryBuilder
{
    use THasConfig;
    use THasOutput;

    protected string $savePath = '';
    protected string $templatePath = '';
    protected array $hooks = [
        'one-before',
        'one-after',
        'one-as-array-before',
        'one-as-array-after',
        'all-before',
        'all-after',
        'all-as-array-before',
        'all-as-array-after',
        'create-before',
        'create-after',
        'update-before',
        'update-after',
        'delete-before',
        'delete-after',
        'drop-before',
        'drop-after'
    ];
    protected array $placeholders = [];

    public function build(array $driverConfig): void
    {
        $this->placeholders = [];

        $template = file_get_contents($this->getPathTemplate() . '/repository_template.txt');
        $repoContent = '';

        foreach ($driverConfig['tables'] as $tableName => $tableConfig) {
            $ns = $tableConfig['namespace'] ?? 'extas\components\repositories';

            $baseInfo  = $this->getCodeForBaseInfo($ns, $tableName, $tableConfig, $driverConfig);
            $hooksInfo = $this->getCodeForHooks($tableName, $tableConfig);
            $codeInfo  = $this->getCodeForCode($tableConfig);

            $info = array_merge($baseInfo, $hooksInfo, $codeInfo);

            $repoContent = str_replace(
                $this->placeholders,
                $info,
                $template
            );

            file_put_contents($this->getPathSave() . '/Repository'.ucfirst($tableName) .'.php', $repoContent);
            
            $this->registerAliases($ns, $tableName, $tableConfig);
            $this->addToOutput('[OK] Installed table "' . $tableName . '"');
        }

        SystemContainer::reset();
    }

    public function getPathSave(): string
    {
        return $this->config[static::FIELD__PATH_SAVE] ?? '';
    }

    public function getPathTemplate(): string
    {
        return $this->config[static::FIELD__PATH_TEMPLATE] ?? '';
    }

    protected function registerAliases(string $ns, string $tableName, array $tableConfig): void
    {
        if (!isset($tableConfig['aliases'])) {
            $tableConfig['aliases'] = [];
        }

        $tableConfig['aliases'][] = $tableName;

        foreach ($tableConfig['aliases'] as $alias) {
            SystemContainer::saveItem($alias, $ns . '\\Repository' . ucfirst($tableName));
            $this->addToOutput('[OK] Registered alias "' . $alias . '"');
        }
    }

    protected function getCodeForBaseInfo(string $ns, string $tableName, array $tableConfig, array $driverConfig): array
    {
        $this->placeholders = array_merge($this->placeholders, [
            '{namespace}',
            '{uc_table_name}',
            '{name}',
            '{scope}',
            '{pk}',
            '{item_class}',
            '{subject}',
            '{driver-class}',
            '{driver-options}'
        ]);

        $driverClass = $driverConfig['class'];
        $driverOptions = '';
        foreach ($driverConfig['options'] as $name => $value) {
            $driverOptions .= "'".$name."' => '" . $value . "', ";
        }

        return [
            $ns,
            ucfirst($tableName),
            $tableName,
            $tableConfig['scope'] ?? 'extas',
            $tableConfig['pk'] ?? 'id',
            $tableConfig['item_class'] ?? Item::class,
            $tableConfig['subject'] ?? $tableName,
            $driverClass,
            $driverOptions
        ];
    }

    protected function getCodeForHooks(string $tableName, array $tableConfig): array
    {
        $code = [];

        foreach($this->hooks as $hook) {
            $this->placeholders[] = '{' . $hook . '-hook}';
            $code[] = $this->createHook($hook, $tableName, $tableConfig);
        }

        return $code;
    }

    protected function getCodeForCode(array $tableConfig): array
    {
        $code = [];

        foreach($this->hooks as $hook) {
            $this->placeholders[] = '{' . $hook . '-code}';
            $code[] = $this->createCode($hook, $tableConfig);
        }

        return $code;
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
                file_get_contents($this->getPathTemplate() . '/hooks_templates/' . $hookName . '.txt')
            );
        }

        return '';
    }

    protected function createCode(string $hookName, array $config): string
    {
        if (isset($config['code'], $config['code'][$hookName])) {
            return is_file($config['code'][$hookName]) 
                ? include $config['code'][$hookName]
                : $config['code'][$hookName];
        }

        return '';
    }
}
