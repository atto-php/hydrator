<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Scalars\Nullable\WithDefault;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;

final class Integers implements Fixture
{
    public function __construct(
        private ?int $default = 42,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private ?int $json = 42,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private ?int $commaDelimited = 42,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private ?int $pipeDelimited = 42,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $newSelf = fn($p) => new self(...array_fill(0, 4, $p));

        return [
            'null' => $newSelf(null),
            'defaults only' => new self(),
            '0' => $newSelf(0),
            '1' => $newSelf(1),
            '123456789' => $newSelf(123456789),
        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'default' => $this->default,
            'json' => $this->json,
            'commaDelimited' => $this->commaDelimited,
            'pipeDelimited' => $this->pipeDelimited,
        ];
    }
}
