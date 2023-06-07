<?php
namespace extas\interfaces\repositories;

use extas\components\exceptions\AlreadyExist;
use extas\components\exceptions\MissedOrUnknown;
use extas\interfaces\IHaveUUID;
use extas\interfaces\IItem;
use extas\interfaces\repositories\IRepository;

/**
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IRepoItem
{
    /**
     * Set uuid
     *
     * @param IHaveUUID $item
     * @return void
     */
    public static function setId(IHaveUUID &$item): void;
    
    /**
     * Set uuid to the specific field
     *
     * @param IItem $item
     * @param string $field
     * @return void
     */
    public static function setUuid(IItem &$item, string $field, int $version = 4): void;

    /**
     * Throw exception if item with $fields is already exist.
     *
     * @param IRepository $repo
     * @param IItem $item
     * @param array $fields
     * @return void
     * @throws AlreadyExist
     */
    public static function throwIfExist(IRepository $repo, IItem &$item, array $fields): void;
    
    /**
     * Throw exception if any of required $fields is missed.
     *
     * @param IItem $item
     * @param array $fields
     * @return void
     * @throws MissedOrUnknown
     */
    public static function throwIfMissedFields(IItem &$item, array $fields): void;

    /**
     * Hash the $fields with sha1
     *
     * @param IItem $item
     * @param array $fields
     * @return void
     */
    public static function sha1(IItem &$item, array $fields): void;
    
    /**
     * Encrypt the $fields with $encryptionDriver.
     *
     * @param IItem $item
     * @param array $fields
     * @param string $encryptionDriver
     * @return void
     */
    public static function encrypt(IItem $item, array $fields, string $encryptionDriver = 'openssl_key'): void;

    /**
     * Run several checks at once
     *
     * @param IRepository $repo
     * @param IItem $item
     * @param array $checks
     * @return void
     * @throws AlreadyExist
     * @throws MissedOrUnknown
     */
    public static function multiple(IRepository $repo, IItem $item, array $checks): void;
}
