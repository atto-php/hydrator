<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Scalars\Nullable;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;

final class BoolsDefaultNull implements Fixture
{
    public function __construct(
        private ?bool $default = null,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private ?bool $json = null,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private ?bool $commaDelimited = null,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private ?bool $pipeDelimited = null,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $fillProperties = fn($p) => array_fill(0, 4, $p);

        return [
            'null default' => new self(),
            'null' => new self(...$fillProperties(null)),
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
