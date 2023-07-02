<?php
namespace extas\interfaces;

interface IHaveOutput
{
    public function getOutput(): array;
    public function appendOutput(array $output, string $prefix = ''): void;
}
