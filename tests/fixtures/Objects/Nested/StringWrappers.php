<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures\Objects\Nested;

use Atto\Hydrator\{TestFixtures, TestFixtures\Fixture};
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;

final class StringWrappers implements Fixture
{
    public function __construct(
        private TestFixtures\Objects\StringWrappers $default,
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private TestFixtures\Objects\StringWrappers $json,
        #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
        private TestFixtures\Objects\StringWrappers $commaDelimited,
        #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
        private TestFixtures\Objects\StringWrappers $pipeDelimited,
    ) {
    }

    /** @return Fixture[] */
    public static function getExampleObjects(): array
    {
        return array_map(
            fn($p) => new self(...array_fill(0, 4, $p)),
            TestFixtures\Objects\StringWrappers::getExampleObjects(),
        );
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
