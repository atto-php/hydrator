<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithScalars;

use Atto\Hydrator\Attribute\Hydratable;
use Atto\Hydrator\TestFixtures\Fixture;

#[Hydratable]
final class Strings implements Fixture
{
    private string $unset;
    private ?string $unsetNullable;

    public function __construct(
        private string $basic,
        private ?string $nullable,
        private string $withDefault = 'Hello',
        private ?string $nullableWithDefault = 'World',
        private ?string $nullableWithNullDefault = null,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $fillProperties = fn($p) => array_fill(0, 5, $p);

        return [
            'empty string' => new self(...$fillProperties('')),
            'non-empty string' => new self(...$fillProperties('Hello')),
            'set nullable strings to null' => new self('Hello', null, 'World', null, null),
            'rely on defaults' => new self('Howdy', 'Planet'),
        ];
    }

    public function getExpectedObject(): Strings
    {
        $expectedObject = clone $this;
        $expectedObject->unsetNullable = null;

        return $expectedObject;
    }

    public function getExpectedArray(): array
    {
        return [
            // unset will not be here
            'unsetNullable' => null,
            'basic' => $this->basic,
            'nullable' => $this->nullable,
            'withDefault' => $this->withDefault,
            'nullableWithDefault' => $this->nullableWithDefault,
            'nullableWithNullDefault' => $this->nullableWithNullDefault,
        ];
    }
}
