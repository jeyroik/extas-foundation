<?php
namespace extas\components\collections;

/**
 * @method buildItem(string $name, bool $errorIfMissed = false)
 */
trait TBuildAll
{
    public function buildAll(array $names = [], bool $errorIfMissed = false): array
    {
        if (empty($names)) {
            $names = array_keys($this->config);
        }

        $result = [];

        foreach ($names as $name) {
            $result[$name] = $this->buildOne($name, $errorIfMissed);
        }

        return $result;
    }
}
