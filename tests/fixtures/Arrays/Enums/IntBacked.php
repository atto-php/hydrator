<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Arrays\Enums;

use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\TestFixtures\Fixture;
use Atto\Hydrator\TestFixtures\Mocks\Enums\IntDummy;

final class IntBacked implements Fixture
{
    public function __construct(
        #[Subtype(IntDummy::class)]
        private array $default,
        //#[Subtype(IntDummy::class)]
        //#[SerializationStrategy(SerializationStrategyType::Json)]
        //private array $json,
        //#[Subtype(IntDummy::class)]
        //#[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        //private array $commaDelimited,
        //#[Subtype(IntDummy::class)]
        //#[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        //private array $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $newSelf = fn($p) => new self(...array_fill(0, 1, $p));

        return [
            'empty list' => $newSelf([]),
            '[Zero]' => $newSelf([IntDummy::Zero]),
            '[One]' => $newSelf([IntDummy::One]),
            '[Zero, One]' => $newSelf([IntDummy::Zero, IntDummy::One]),
        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'default' => json_encode(array_map(
                fn($e) => $e->value,
                $this->default,
            )),
            //'json' => json_encode(array_map(
            //    fn($e) => $e->value,
            //    $this->json,
            //)),
            //'commaDelimited' => implode(',', array_map(
            //    fn($e) => $e->value,
            //    $this->commaDelimited,
            //)),
            //'pipeDelimited' => implode('|', array_map(
            //    fn($e) => $e->value,
            //    $this->pipeDelimited,
            //)),
        ];
    }
}
