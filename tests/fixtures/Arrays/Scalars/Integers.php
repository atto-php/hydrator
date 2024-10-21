<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Arrays\Scalars;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\TestFixtures\Fixture;

final class Integers implements Fixture
{
    /**
     * @param int[] $default
     * @param int[] $json
     * @param int[] $commaDelimited
     * @param int[] $pipeDelimited
     */
    public function __construct(
        #[Subtype('int')]
        private array $default,
        #[Subtype('int')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $json,
        #[Subtype('int')]
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private array $commaDelimited,
        #[Subtype('int')]
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private array $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $newSelf = fn($p) => new self(...array_fill(0, 4, $p));

        return [
            //'empty array' => $newSelf([]),
            '[0]' => $newSelf([0]),
            '[1]' => $newSelf([1]),
            '[0, 1, 2]' => $newSelf([0, 1, 2]),
        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'default' => json_encode($this->default),
            'json' => json_encode($this->json),
            'commaDelimited' => implode(',', $this->commaDelimited),
            'pipeDelimited' => implode('|', $this->pipeDelimited),
        ];
    }
}
