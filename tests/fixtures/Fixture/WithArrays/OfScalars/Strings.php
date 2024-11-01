<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Fixture\WithArrays\OfScalars;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\TestFixtures\Fixture;

final class Strings implements Fixture
{
    /**
     * @param string[] $basic
     * @param string[] $json
     * @param string[] $commaDelimited
     * @param string[] $pipeDelimited
     */
    public function __construct(
        #[Subtype('string')]
        private array $basic,
        #[Subtype('string')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $json,
        #[Subtype('string')]
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private array $commaDelimited,
        #[Subtype('string')]
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
            '[""]' => $newSelf(['']),
            '["Hello"]' => $newSelf(['Hello']),
            '["Hello", "world"]' => $newSelf(['Hello', 'world']),
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
