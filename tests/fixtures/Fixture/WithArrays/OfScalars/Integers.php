<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithArrays\OfScalars;

use Atto\Hydrator\Attribute\Hydratable;
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\TestFixtures\Fixture;

#[Hydratable]
final class Integers implements Fixture
{
    /**
     * @param int[] $basic
     * @param int[] $json
     * @param int[] $commaDelimited
     * @param int[] $pipeDelimited
     */
    public function __construct(
        #[Subtype('int')]
        private array $basic,
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

    public function getExpectedObject(): Fixture
    {
        return $this;
    }

    public function getExpectedArray(): array
    {
        return [
            'basic' => json_encode($this->basic),
            'json' => json_encode($this->json),
            'commaDelimited' => implode(',', $this->commaDelimited),
            'pipeDelimited' => implode('|', $this->pipeDelimited),
        ];
    }
}
