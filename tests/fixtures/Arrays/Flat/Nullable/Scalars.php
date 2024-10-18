<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Arrays\Flat\Nullable;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;

final class Scalars implements Fixture
{
    public function __construct(
        private ?array $default,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private ?array $json,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private ?array $commaDelimited,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private ?array $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $newSelf = fn($p) => new self(...array_fill(0, 4, $p));

        return [
            'null' => $newSelf(null),
            'list of nulls' => $newSelf([null, null]),
            'list of bools' => $newSelf([true, false]),
            'list of floats' => $newSelf(['3.14', '9.81']),
            'list of integers' => $newSelf([1, 2, 3]),
            'list of strings' => $newSelf(['Hello', 'World']),
        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'default' => $this->default !== null ?
                json_encode($this->default) :
                null,
            'json' => $this->json !== null ?
                json_encode($this->json) :
                null,
            'commaDelimited' => $this->commaDelimited !== null ?
                implode(',', $this->commaDelimited) :
                null,
            'pipeDelimited' => $this->pipeDelimited !== null ?
                implode('|', $this->pipeDelimited) :
                null,
        ];
    }
}
