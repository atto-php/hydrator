<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Objects;

use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;
use Atto\Hydrator\TestFixtures\Mocks\WrappedString;

final class StringWrappers implements Fixture
{
    public function __construct(
        #[HydrationStrategy(HydrationStrategyType::String)]
        private WrappedString $default,
        #[HydrationStrategy(HydrationStrategyType::String)]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private WrappedString $json,
        #[HydrationStrategy(HydrationStrategyType::String)]
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private WrappedString $commaDelimited,
        #[HydrationStrategy(HydrationStrategyType::String)]
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private WrappedString $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $newSelf = fn($p) => new self(...array_fill(0, 4, $p));

        return [
            'empty string' => $newSelf(new WrappedString('')),
            '"Hello"' => $newSelf(new WrappedString('Hello, ')),
            '"World!"' => $newSelf(new WrappedString('World!')),
        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'default' => $this->default->__toString(),
            'json' => $this->json->__toString(),
            'commaDelimited' => $this->pipeDelimited->__toString(),
            'pipeDelimited' =>  $this->pipeDelimited->__toString(),
        ];
    }
}
