<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Arrays\Flat;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;

final class Scalars implements Fixture
{
    public function __construct(
        private array $default,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $json,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private array $commaDelimited,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private array $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $newSelf = fn($p) => new self(...array_fill(0, 4, $p));

        return [
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
            'default' => json_encode($this->default),
            'json' => json_encode($this->json),
            'commaDelimited' => implode(',', $this->commaDelimited),
            'pipeDelimited' => implode('|', $this->pipeDelimited)
        ];
    }
}
