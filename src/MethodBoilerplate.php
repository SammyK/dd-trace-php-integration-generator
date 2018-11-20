<?php

namespace DDGen;

use Roave\BetterReflection\Reflection\ReflectionMethod;
use Roave\BetterReflection\Reflection\ReflectionParameter;

final class MethodBoilerplate
{
    // @TODO Throw this into a twig template or something
    private const TEMPLATE = <<<EOT
// %s %s::%s ( %s )
dd_trace('%2\$s', '%3\$s', function (...\$args) {
    return %2\$sIntegration::trace(\$this, '%3\$s', \$args);
});


EOT;

    /**
     * @var ReflectionMethod
     */
    private $method;

    /**
     * @var ReflectionParameter[]
     */
    private $params;

    public function __construct(ReflectionMethod $method)
    {
        $this->method = $method;
        $this->params = $this->method->getParameters();
    }

    public function makeBoilerplate(): string
    {
        $params = [];
        $requiredCount = $this->method->getNumberOfRequiredParameters();
        $paramCount = $this->method->getNumberOfParameters();
        $i = 0;
        foreach ($this->params as $param) {
            # // bool Memcached::add ( string $key , mixed $value [, int $expiration ] )
            $prefix = '';
            $suffix = '';
            if ($i === $requiredCount - 1) {
                $prefix = '[ ';
            }
            if ($i === $paramCount - 1) {
                $suffix = ' ]';
            }
            $params[] = sprintf(
                '%s%s $%s%s',
                $prefix,
                $param->getType(),
                $param->getName(),
                $suffix
            );
            $i++;
        }
        return sprintf(
            self::TEMPLATE,
            $this->method->getReturnType() ?: implode('|', $this->method->getDocBlockReturnTypes()),
            $this->method->getDeclaringClass()->getName(),
            $this->method->getName(),
            implode(', ', $params)
        );
    }
}
