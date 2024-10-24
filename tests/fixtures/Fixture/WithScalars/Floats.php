<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithScalars;

use Atto\Hydrator\TestFixtures\Fixture;

final class Floats implements Fixture
{
    public function __construct(
        private float $basic,
        private ?float $nullable,
        private float $withDefault = 0.0,
        private ?float $nullableWithDefault = 1.1,
        private ?float $nullableWithNullDefault = null,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return [
            '0.0' => new self(...array_fill(0, 5, 0.0)),
            '1.1' => new self(...array_fill(0, 5, 1.1)),
            'set nullable floats to null' => new self(3.14, null, 9.81, null, null),
            'rely on defaults' => new self(3.14, 9.81),
        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'basic' => $this->basic,
            'nullable' => $this->nullable,
            'withDefault' => $this->withDefault,
            'nullableWithDefault' => $this->nullableWithDefault,
            'nullableWithNullDefault' => $this->nullableWithNullDefault
        ];
    }
}
