<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Attributes\Arrays\Flat;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;

final class Scalars implements Fixture
{
    public function __construct(
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private array $integers,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private array $strings,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private array $bools,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private array $floats,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return [
            new self(
                [0, 1, 2, 3],
                ['Hello, ', 'World!'],
                [true, false],
                [3.14, 9.81],
            ),

        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'integers' => implode(',', $this->integers),
            'strings' => implode(',', $this->strings),
            'bools' => implode(',', $this->bools),
            'floats' => implode(',', $this->floats),
        ];
    }
}
