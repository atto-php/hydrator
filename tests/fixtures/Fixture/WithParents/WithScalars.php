<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithParents;

use Atto\Hydrator\TestFixtures\Fixture\WithScalars\Bools;

final class WithScalars extends Bools
{
    /** @return WithScalars[] */
    public static function getExampleObjects(): array
    {
        return [
            'false' => new self(...array_fill(0, 5, false)),
            'true' => new self(...array_fill(0, 5, true)),
            'set nullable bools to null' => new self(true, null, false, null, null),
            'rely on defaults' => new self(true, false),
        ];
    }

    public function getExpectedObject(): WithScalars
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
