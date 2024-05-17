<?php

declare(strict_types=1);

namespace Atto\Hydrator;

final class ClassName
{
    public readonly string $namespace;

    private function __construct(
        public readonly array $namespaceParts,
        public readonly string $name
    ) {
        $this->namespace = implode('\\', $this->namespaceParts);
    }

    public static function fromFullyQualifiedName(string $className): self
    {
        $classParts = explode('\\', $className);
        $name = array_pop($classParts);

        return new self($classParts, $name);
    }

    public function asClassString(bool $fullyQualified = true): string
    {
        return $fullyQualified ?
            sprintf('\\%s\\%s::class', implode('\\', $this->namespaceParts), $this->name) :
            sprintf('%s::class', $this->name)
        ;
    }

    public function asString(bool $fullyQualified = true): string
    {
        return $fullyQualified ?
            sprintf('\\%s\\%s', implode('\\', $this->namespaceParts), $this->name) :
            sprintf('%s', $this->name)
            ;
    }

    public function removeNamespacePrefix(string $namespacePrefix): self
    {
        $namespaceParts = explode('\\', $namespacePrefix);
        $newNamespace = array_slice($this->namespaceParts, count($namespaceParts));

        return new self($newNamespace, $this->name);
    }

    public function addNamespacePrefix(string $namespacePrefix): self
    {
        $namespaceParts = explode('\\', $namespacePrefix);
        $newNamespace = [...$namespaceParts, ...$this->namespaceParts];

        return new self($newNamespace, $this->name);
    }
}
