<?php
namespace extas\interfaces\commands;

interface IHaveEntitiesOptions
{
    public const OPTION__ENTITY_APP_FILENAME = 'entity_app_filename';
    public const OPTION__ENTITY_PCKGS_FILENAME = 'entity_pckgs_filename';

    public function attachEntitiesOptions(): void;
}
