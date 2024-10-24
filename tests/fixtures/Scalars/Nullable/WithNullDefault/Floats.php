<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Scalars\Nullable\WithNullDefault;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;

final class Floats implements Fixture
{
    public function __construct(
        private ?float $default = null,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private ?float $json = null,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private ?float $commaDelimited = null,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private ?float $pipeDelimited = null,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $fillProperties = fn($p) => array_fill(0, 4, $p);

        return [
            'default null' => new self(),
            'null' => new self(...$fillProperties(null)),
            '0.0' => new self(...$fillProperties(0.0)),
            '1.1' => new self(...$fillProperties(1.1)),
            '3.14' => new self(...$fillProperties(3.14)),
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
