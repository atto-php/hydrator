<?php

declare(strict_types=1);

namespace Atto\Hydrator;

use Atto\CodegenTools\ClassDefinition\SimplePHPClassDefinition;
use Atto\Hydrator\Attribute\Hydratable;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

final class HydratorProvider
{
    private Builder $builder;
    public function __construct(
        private string $directory,
        private string $baseNamespace,
        private string $hydratorNamespace
    ) {
        $this->builder = new Builder();
    }

    public function provideFile(): \Generator
    {
        $classList = $hydratableClasses = $this->getHydratableClasses();

        while ($class = array_pop($classList)) {
            $hydratorCode = $this->builder->build($class, $this->hydratorNamespace, $this->baseNamespace);

            foreach ($hydratorCode->getSubHydrators() as $subHydrator) {
                if (!in_array($subHydrator, $hydratableClasses)) {
                    array_push($hydratableClasses, $subHydrator);
                    array_push($classList, $subHydrator);
                }
            }

            yield new SimplePHPClassDefinition(
                $hydratorCode->getHydratorClassName()->namespace,
                $hydratorCode->getHydratorClassName()->name,
                "<?php\n\n" . $hydratorCode
            );
        };
    }

    private function getHydratableClasses(): array
    {
        $astLocator = (new BetterReflection())->astLocator();
        $directoriesSourceLocator = new DirectoriesSourceLocator([$this->directory], $astLocator);
        $reflector = new DefaultReflector($directoriesSourceLocator);
        $classes = [];

        foreach($reflector->reflectAllClasses() as $class) {
            if (count($class->getAttributesByName(Hydratable::class))) {
                $classes[] = $class->getName();
            }
        }

        return $classes;
    }
}