<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template;

use Atto\Hydrator\ClassName;

final class HydratorClass
{
    private const CLASS_CODE = <<<'EOF'
        namespace %s;
        
        final class %s 
        {
            public const PROPERTIES = [
                '%s'
            ];
            
            private readonly array $hydrateMethods;
            private readonly array $extractMethods;
            private readonly \Doctrine\Instantiator\Instantiator $instantiator;
            
            public function __construct(
                %s
            ) {
                $hydrate = $extract = $properties = [];
                %s
                $this->hydrateMethods = $hydrateMethods;
                $this->extractMethods = $extractMethods;
                $this->instantiator = new \Doctrine\Instantiator\Instantiator();
            }
            
            public function create(array &$data): object
            {
                $object = $this->instantiator->instantiate(%s::class);
                $this->hydrate($data, $object);
                return $object;
            }
            
            public function hydrate(array &$data, object $object): void
            {
                %s
            }
            
            public function extract(object $object): array
            {
                $data = [];
                %s
                return $data;
            }
        }
        EOF;

    private const CONSTRUCTOR_CODE = <<<'EOF'
        $%s[%s] = %s
        EOF;

    private const METHOD_CODE = <<<'EOF'
        $this->%s[%s]->__invoke($object, $data);
        EOF;


    private int $parameterCount = 0;
    private array $subHydrators = [];
    private array $properties = [];
    private array $hydrateMethods = [];
    private array $extractMethods = [];

    public function __construct(
        private readonly ClassName $hydratorClassName,
        private readonly string $targetClassName,
    ) {
    }

    public function getHydratorClassName(): ClassName
    {
        return $this->hydratorClassName;
    }

    public function getSubHydrators(): array
    {
        return array_keys($this->subHydrators);
    }

    public function addSubHydrator(ClassName $className, ClassName $hydratorName): void
    {
        if (isset($this->subHydrators[$className->asString()])) {
            return;
        }

        $this->subHydrators[$className->asString()] = [$hydratorName, 'p' . ++$this->parameterCount];
    }

    public function addHydrateMethod(string|\Stringable $method): void
    {
        $this->hydrateMethods[] = $method;
    }

    public function addExtractMethod(string|\Stringable $method): void
    {
        $this->extractMethods[] = $method;
    }

    public function __toString(): string
    {
        $constructorParameters = $constructorCode = $hydrateCode = $extractCode = '';
        foreach ($this->subHydrators as $subHydrator => list($hydratorName, $parameterName)) {
            $subHydratorTemplate = new SubHydrator($subHydrator, $parameterName);
            $constructorCode .= $subHydratorTemplate;
            $constructorParameters .= sprintf(
                '%sHydrator $%s,',
                $hydratorName->asString(),
                $parameterName
            );
        }

        foreach ($this->hydrateMethods as $key => $hydrateMethod) {
            $constructorCode .= sprintf(
                self::CONSTRUCTOR_CODE,
                'hydrateMethods',
                $key,
                 (string) $hydrateMethod
            );
            if (is_numeric($key)) {
                $hydrateCode .= sprintf(self::METHOD_CODE, 'hydrateMethods', $key);
            }
        }

        foreach ($this->extractMethods as $key => $extractMethod) {
            $constructorCode .= sprintf(self::CONSTRUCTOR_CODE, 'extractMethods', $key, $extractMethod);
            if (is_numeric($key)) {
                $extractCode .= sprintf(self::METHOD_CODE, 'extractMethods', $key);
            }
        }

        $properties = implode("',\n        '", $this->properties);

        return sprintf(
            self::CLASS_CODE,
            $this->hydratorClassName->namespace,
            $this->hydratorClassName->name,
            $properties,
            $constructorParameters,
            $constructorCode,
            $this->targetClassName,
            $hydrateCode,
            $extractCode
        );
    }

    public function addProperty(string $propertyName): void
    {
        $this->properties[] = $propertyName;
    }
}