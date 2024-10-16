<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Objects\Flat;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\TestFixtures\Fixture;
use DateTime;

final class DateTimes implements Fixture
{
    public function __construct(
        private DateTime $default,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private DateTime $json,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private DateTime $commaDelimited,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private DateTime $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $newSelf = fn($p) => new self(...array_fill(0, 4, new DateTime($p)));

        return [
            '1970-01-01' => $newSelf('1970-01-01'),
        ];
    }

    public function getExpectedArray(): array
    {
        return [
            'default' => $this->default->format(DATE_ATOM),
            'json' => $this->json->format(DATE_ATOM),
            'commaDelimited' => $this->commaDelimited->format(DATE_ATOM),
            'pipeDelimited' => $this->pipeDelimited->format(DATE_ATOM),
        ];
    }
}
