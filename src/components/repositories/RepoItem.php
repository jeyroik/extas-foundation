<?php
namespace extas\components\repositories;

use extas\components\exceptions\AlreadyExist;
use extas\components\exceptions\MissedOrUnknown;
use extas\components\UUID;
use extas\interfaces\IHasAliases;
use extas\interfaces\IHasName;
use extas\interfaces\IHaveConfig;
use extas\interfaces\IHaveUUID;
use extas\interfaces\repositories\IRepoItem;
use extas\interfaces\repositories\IRepository;

/**
 * @author jeyroik <jeyroik@gmail.com>
 */
class RepoItem implements IRepoItem
{
    public static function setId(IHaveUUID &$item): void
    {
        UUID::setId($item);
    }

    public static function setUuid(IHaveConfig &$item, string $field, int $version = 4): void
    {
        UUID::setUuid($item, $field, $version);
    }

    public static function throwIfExist(IRepository $repo, IHaveConfig &$item, array $fields): void
    {
        $where = [];

        foreach ($fields as $name) {
            $where[$name] = $item[$name] ?? '';
        }

        $exists = $repo->one($where);

        if ($exists) {
            throw new AlreadyExist($repo->getName());
        }
    }

    public static function throwIfMissedFields(IHaveConfig &$item, array $fields): void
    {
        $missed = [];

        foreach ($fields as $name) {
            if (!isset($item[$name])) {
                $missed[] = $name;
            }
        }

        if (!empty($missed)) {
            throw new MissedOrUnknown(implode(', ', $missed));
        }
    }

    public static function sha1(IHaveConfig &$item, array $fields): void
    {
        foreach ($fields as $name) {
            $item[$name] = sha1($item[$name] ?? '');
        }
    }

    public static function encrypt(IHaveConfig $item, array $fields, string $encryptionDriver = 'openssl_key'): void
    {
        //todo in v0.3.0
    }

    public static function multiple(IRepository $repo, IHaveConfig $item, array $checks): void
    {
        foreach ($checks as $checkMethod => $checkOptions) {
            match ($checkMethod) {
                'setId' => static::setId($item),
                'setUuid' => static::setUuid($item, $checkOptions['field'], $checkOptions['v'] ?? 4),
                'throwIfExist' => static::throwIfExist($repo, $item, $checkOptions),
                'throwIfMissedFields' => static::throwIfMissedFields($item, $checkOptions),
                'sha1' => static::sha1($item, $checkOptions),
                'encrypt' => static::encrypt($item, $checkOptions['fields'], $checkOptions['driver'] ?? 'openssl_key'),
                default => ''
            };
        }
    }

    public static function addNameToAliases(IHaveConfig|IHasAliases|IHasName &$item): void
    {
        $item->addAlias($item->getName());
    }
}
