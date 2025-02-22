<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithScalars;

use Atto\Hydrator\Attribute\Hydratable;
use Atto\Hydrator\TestFixtures\Fixture;

#[Hydratable]
class Bools implements Fixture
{
    private bool $unset;
    protected ?bool $unsetNullable;

    public function __construct(
        private bool $basic,
        private ?bool $nullable,
        private bool $withDefault = true,
        private ?bool $nullableWithDefault = false,
        private ?bool $nullableWithNullDefault = null,
    ) {
    }

    /** @return Bools[] */
    public static function getExampleObjects(): array
    {
        return [
            'false' => new self(...array_fill(0, 5, false)),
            'true' => new self(...array_fill(0, 5, true)),
            'set nullable bools to null' => new self(true, null, false, null, null),
            'rely on defaults' => new self(true, false),
        ];
    }

    public function getExpectedObject(): Bools
    {
        $expectedObject = clone $this;
        $expectedObject->unsetNullable = null;

        return $expectedObject;
    }

    public function getExpectedArray(): array
    {
        return [
            // unset will not be extracted
            'unsetNullable' => null,
            'basic' => $this->basic,
            'nullable' => $this->nullable,
            'withDefault' => $this->withDefault,
            'nullableWithDefault' => $this->nullableWithDefault,
            'nullableWithNullDefault' => $this->nullableWithNullDefault,
        ];
    }
}
