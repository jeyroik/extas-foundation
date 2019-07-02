<?php
namespace extas\components\repositories\drivers;

use extas\components\repositories\clients\ClientMongo;

/**
 * Class DriverMongo
 *
 * @package extas\components\repositories
 * @author aivanov@fix.ru
 */
class DriverMongo extends Driver
{
    protected $clientClass = ClientMongo::class;
}
