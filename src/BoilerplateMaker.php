<?php

namespace DDGen;

use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;

final class BoilerplateMaker
{
    private const OUTPUT_FILE_NAME = './%sIntegration.php';

    private $className;

    /**
     * @var ReflectionClass
     */
    private $classInfo;

    /**
     * @var ReflectionMethod[]
     */
    private $methods;

    public function __construct(string $className)
    {
        $this->className = $className;
        $this->classInfo = (new BetterReflection)
            ->classReflector()
            ->reflect($this->className);
        $this->methods = $this->classInfo->getMethods();
    }

    public function makeFile(): string
    {
        $outputFile = sprintf(self::OUTPUT_FILE_NAME, $this->className);
        $file = fopen($outputFile, 'wb');
        foreach ($this->methods as $method) {
            fwrite($file, (new MethodBoilerplate($method))->makeBoilerplate());
        }
        fclose($file);
        return $outputFile;
    }
}
