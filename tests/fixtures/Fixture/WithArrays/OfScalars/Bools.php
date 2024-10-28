<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithArrays\OfScalars;

use Atto\Hydrator\Attribute\Hydratable;
use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\TestFixtures\Fixture;

final class Bools implements Fixture
{
    /**
     * @param bool[] $basic
     * @param bool[] $serialisationJson
     * @param bool[] $serialisationCommaDelimited
     * @param bool[] $serialisationPipeDelimited
     */
    public function __construct(
        #[Subtype('bool')]
        private array $basic,
        #[Subtype('bool')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $serialisationJson,
        #[Subtype('bool')]
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private array $serialisationCommaDelimited,
        #[Subtype('bool')]
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private array $serialisationPipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $newSelf = fn($p) => new self(...array_fill(0, 5, $p));

        return [
            //'empty array' => $newSelf([]),
            '[null]' => $newSelf([null]),
            '[false]' => $newSelf([false]),
            '[true]' => $newSelf([true]),
            '[true, false, true]' => $newSelf([true, false, true]),
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
            'serialisationJson' => json_encode($this->serialisationJson),
            'serialisationCommaDelimited' => implode(',', $this->serialisationCommaDelimited),
            'serialisationPipeDelimited' => implode('|', $this->serialisationPipeDelimited),
        ];
    }
}
