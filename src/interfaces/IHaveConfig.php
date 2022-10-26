<?php
namespace extas\interfaces;

interface IHaveConfig
{
    public function __construct(array $config = []);

    public function __toArray(): array;
}
