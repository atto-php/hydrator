<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Arrays\Nested;

use Atto\Hydrator\TestFixtures\Fixture;

final class Scalars implements Fixture
{
    public function __construct(
        private array $integers,
        private array $strings,
        private array $bools,
        private array $floats,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return [
            new self(
                [[0, 1, 2], [3, 4, 5], [6, 7, 8]],
                [['Hello, ', 'World!'], ['Howdy, ', 'Planet?']],
                [[true, true], [true, false], [false, true], [false, false]],
                [[3.14, 9.81], [0.0, 1.1]],
            ),

        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'integers' => json_encode($this->integers),
            'strings' => json_encode($this->strings),
            'bools' => json_encode($this->bools),
            'floats' => json_encode($this->floats),
        ];
    }
}
