<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithScalars;

use Atto\Hydrator\TestFixtures\Fixture;

final class Integers implements Fixture
{
    private int $unset;
    private ?int $unsetNullable;

    public function __construct(
        private int $basic,
        private ?int $nullable,
        private int $withDefault = 0,
        private ?int $nullableWithDefault = 1,
        private ?int $nullableWithNullDefault = null,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return [
            'false' => new self(...array_fill(0, 5, 0)),
            'true' => new self(...array_fill(0, 5, 1)),
            'set nullable ints to null' => new self(0, null, 1, null, null),
            'rely on defaults' => new self(123, 456),
        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'basic' => $this->basic,
            'nullable' => $this->nullable,
            'withDefault' => $this->withDefault,
            'nullableWithDefault' => $this->nullableWithDefault,
            'nullableWithNullDefault' => $this->nullableWithNullDefault,
            // unset will not be here
            'unsetNullable' => null,
        ];
    }
}
