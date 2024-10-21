<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Objects\Enums;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;
use Atto\Hydrator\TestFixtures\Mocks\Enums\StringDummy;

final class StringBacked implements Fixture
{
    public function __construct(
        private StringDummy $default,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private StringDummy $json,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private StringDummy $commaDelimited,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private StringDummy $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $newSelf = fn($p) => new self(...array_fill(0, 4, $p));

        return [
            'Zero' => $newSelf(StringDummy::Zero),
            'One' => $newSelf(StringDummy::One),
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
