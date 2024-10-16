<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Scalars;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;

final class Bools implements Fixture
{
    public function __construct(
        private bool $default,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private bool $json,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private bool $commaDelimited,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private bool $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $fillProperties = fn($p) => array_fill(0, 4, $p);

        return [
            'true' => new self(...$fillProperties(true)),
            'false' => new self(...$fillProperties(false)),
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
