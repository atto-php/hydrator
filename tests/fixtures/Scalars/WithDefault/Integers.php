<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Scalars\WithDefault;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;

final class Integers implements Fixture
{
    public function __construct(
        private int $default = 42,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private int $json = 42,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private int $commaDelimited = 42,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private int $pipeDelimited = 42,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $fillProperties = fn($p) => array_fill(0, 4, $p);

        return [
            'defaults only' => new self(),
            '0' => new self(...$fillProperties(0)),
            '1' => new self(...$fillProperties(1)),
            '123456789' => new self(...$fillProperties(123456789)),
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
