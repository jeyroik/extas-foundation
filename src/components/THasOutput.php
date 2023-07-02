<?php
namespace extas\components;

trait THasOutput
{
    protected array $output = [];

    public function getOutput(): array
    {
        return $this->output;
    }

    public function appendOutput(array $output, string $prefix = ''): void
    {
        if ($prefix) {
            foreach ($output as $i => $msg) {
                $output[$i] = $prefix . ': ' . $msg;
            }
        }

        $this->output = array_merge($this->output, $output);
    }

    protected function addToOutput(string $message): void
    {
        $this->output[] = $message;
    }
}
