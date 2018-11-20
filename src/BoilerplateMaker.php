<?php

namespace DDGen;

final class BoilerplateMaker
{
    private const OUTPUT_FILE_NAME = './%sIntegration.php';

    private $className;

    public function __construct(string $className)
    {
        $this->className = $className;
        $this->validate();
    }

    private function validate(): void
    {
        if (!class_exists($this->className)) {
            throw new \Exception('Class "'.$this->className.'" does not exist');
        }
    }

    public function makeFile(): string
    {
        return sprintf(self::OUTPUT_FILE_NAME, $this->className);
    }
}
