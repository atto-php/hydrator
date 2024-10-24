<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Objects\Nested;

use Atto\Hydrator\{TestFixtures, TestFixtures\Fixture};
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;

final class Strings implements Fixture
{
    public function __construct(
        private TestFixtures\Scalars\Strings $default,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private TestFixtures\Scalars\Strings $json,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private TestFixtures\Scalars\Strings $commaDelimited,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private TestFixtures\Scalars\Strings $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        $fillProperties = fn($p) => array_fill(
            0,
            4,
            new TestFixtures\Scalars\Strings(...array_fill(0, 4, $p)),
        );

        return [
            'empty string' => new self(...$fillProperties('')),
            'Hello' => new self(...$fillProperties('Hello')),
            'Hello, World!' => new self(...$fillProperties('Hello, World!')),
        ];
    }

    public function getExpectedArray(): array
    {
        $mergeKeys = function (string $parentProperty, array $childProperties) {
            $result = [];
            foreach ($childProperties as $childProperty => $value) {
                $result["{$parentProperty}_{$childProperty}"] = $value;
            }

            return $result;
        };

        return [
            ...$mergeKeys('default', $this->default
                ->getExpectedArray()),
            ...$mergeKeys('json', $this->json
                ->getExpectedArray()),
            ...$mergeKeys('commaDelimited', $this->commaDelimited
                ->getExpectedArray()),
            ...$mergeKeys('pipeDelimited', $this->pipeDelimited
                ->getExpectedArray()),
        ];
    }
}
