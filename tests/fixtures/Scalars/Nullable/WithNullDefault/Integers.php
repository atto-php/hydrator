<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Scalars\Nullable\WithNullDefault;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;

final class Integers implements Fixture
{
    public function __construct(
        private ?int $default = null,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private ?int $json = null,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private ?int $commaDelimited = null,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private ?int $pipeDelimited = null,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $fillProperties = fn($p) => array_fill(0, 4, $p);

        return [
            'default null' => new self(),
            'null' => new self(...$fillProperties(null)),
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
