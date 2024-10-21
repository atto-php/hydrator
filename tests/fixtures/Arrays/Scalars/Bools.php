<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Arrays\Scalars;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\TestFixtures\Fixture;

final class Bools implements Fixture
{
    /**
     * @param bool[] $default
     * @param bool[] $json
     * @param bool[] $commaDelimited
     * @param bool[] $pipeDelimited
     */
    public function __construct(
        #[Subtype('bool')]
        private array $default,
        #[Subtype('bool')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $json,
        #[Subtype('bool')]
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private array $commaDelimited,
        #[Subtype('bool')]
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
            '[false]' => $newSelf([false]),
            '[true]' => $newSelf([true]),
            '[true, false, true]' => $newSelf([true, false, true]),
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
