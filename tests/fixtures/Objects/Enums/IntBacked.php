<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Objects\Enums;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;
use Atto\Hydrator\TestFixtures\Mocks\Enums\IntDummy;

final class IntBacked implements Fixture
{
    public function __construct(
        private IntDummy $default,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private IntDummy $json,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private IntDummy $commaDelimited,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private IntDummy $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $newSelf = fn($p) => new self(...array_fill(0, 4, $p));

        return [
            'Zero' => $newSelf(IntDummy::Zero),
            'One' => $newSelf(IntDummy::One),
        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'default' => $this->default->value,
            'json' => $this->json->value,
            'commaDelimited' => $this->commaDelimited->value,
            'pipeDelimited' => $this->pipeDelimited->value,
        ];
    }
}
