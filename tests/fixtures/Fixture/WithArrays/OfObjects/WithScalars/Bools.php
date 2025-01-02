<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithArrays\OfObjects\WithScalars;

use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\TestFixtures\Fixture;
use RuntimeException;

final class Bools implements Fixture
{
    /**
     * @param array<Fixture\WithScalars\Bools> $basic
     */
    public function __construct(
        #[Subtype(Fixture\WithScalars\Bools::class)]
        #[HydrationStrategy(HydrationStrategyType::Nest)]
        private array $basic,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $subFixtures = Fixture\WithScalars\Bools::getExampleObjects();

        return [
            'empty array' => new self([]),
            'single item' => new self([current($subFixtures)
                ?: throw new RuntimeException('subfixture missing')]),
            'many items' => new self($subFixtures),
        ];
    }

    public function getExpectedObject(): Bools
    {
        return new self(
            basic: array_map(fn($b) => $b->getExpectedObject(), $this->basic),
        );
    }

    public function getExpectedArray(): array
    {
        return [
            'basic' => json_encode(array_map(
                fn($b) => $b->getExpectedArray(),
                $this->basic,
            )),
        ];
    }
}
