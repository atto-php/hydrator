<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithArrays\OfScalars;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\TestFixtures\Fixture;

final class Floats implements Fixture
{
    /**
     * @param float[] $basic
     * @param float[] $json
     * @param float[] $commaDelimited
     * @param float[] $pipeDelimited
     */
    public function __construct(
        #[Subtype('float')]
        private array $basic,
        #[Subtype('float')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $json,
        #[Subtype('float')]
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private array $commaDelimited,
        #[Subtype('float')]
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
            '[0.0]' => $newSelf([0.0]),
            '[1.1]' => $newSelf([1.1]),
            '[3.14, 9.81]' => $newSelf([3.14, 9.81]),
        ];
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
