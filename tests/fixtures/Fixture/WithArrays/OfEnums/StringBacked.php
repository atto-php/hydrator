<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithArrays\OfEnums;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\TestFixtures\Fixture;
use Atto\Hydrator\TestFixtures\Mocks\Enums\{StringDummy};

final class StringBacked implements Fixture
{
    public function __construct(
        #[Subtype(StringDummy::class)]
        private array $default,
        #[Subtype(StringDummy::class)]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $json,
        #[Subtype(StringDummy::class)]
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private array $commaDelimited,
        #[Subtype(StringDummy::class)]
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private array $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $newSelf = fn($p) => new self(...array_fill(0, 4, $p));

        return [
            'empty list' => $newSelf([]),
            'single enum' => $newSelf([StringDummy::Zero]),
            'two enums' => $newSelf([StringDummy::Zero, StringDummy::One]),
        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'default' => json_encode(array_map(
                fn($e) => $e->value,
                $this->default,
            )),
            'json' => json_encode(array_map(
                fn($e) => $e->value,
                $this->json,
            )),
            'commaDelimited' => implode(',', array_map(
                fn($e) => $e->value,
                $this->commaDelimited,
            )),
            'pipeDelimited' => implode('|', array_map(
                fn($e) => $e->value,
                $this->pipeDelimited,
            )),
        ];
    }
}
