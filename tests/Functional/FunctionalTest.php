<?php

declare(strict_types=1);

namespace Atto\Hydrator\Tests\Functional;

use Atto\Hydrator\Builder;
use Atto\Hydrator\TestFixtures\{Arrays, Objects, Scalars};
use Atto\Hydrator\TestFixtures\Fixture;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[CoversClass(Builder::class)]
final class FunctionalTest extends TestCase
{
    private static Builder $builder;

    /**
     * @param class-string $hydrator
     */
    #[Test]
    #[DataProvider('provideFixtures')]
    #[TestDox('It extracts and serialises an object, to an array')]
    public function itExtractsToArray(object $hydrator, Fixture $fixture): void
    {
        self::assertEquals(
            $fixture->getExpectedArray(),
            $hydrator->extract($fixture)
        );
    }

    #[Test]
    #[DataProvider('provideFixtures')]
    #[TestDox('It creates and hydrates an object, from an array')]
    public function itCreatesFromArray(object $hydrator, Fixture $fixture): void
    {
        self::assertEquals(
            $fixture,
            $hydrator->create($fixture->getExpectedArray())
        );
    }

    #[Test]
    #[DataProvider('provideFixtures')]
    #[TestDox('"extract" and "create" form an idempotent operation')]
    public function itIsIdempotent(object $hydrator, Fixture $fixture): void
    {
        $object = $fixture;
        for ($i = 0; $i < 3; $i++) {
            $array = $hydrator->extract($object);
            $object = $hydrator->create($array);
        }

        self::assertEquals($fixture, $object);
        self::assertEquals($fixture->getExpectedArray(), $array);
    }

    public static function provideFixtures(): Generator
    {
        $fixtures = [
            Arrays\Arrays\Scalars::class,
            Arrays\Scalars\Bools::class,
            Arrays\Scalars\Floats::class,
            Arrays\Scalars\Integers::class,
            Arrays\Scalars\Strings::class,
            Objects\DateTimes::class,
            Objects\Enums\IntBacked::class,
            Objects\Enums\StringBacked::class,
            Objects\Nested\Bools::class,
            Objects\Nested\DateTimes::class,
            Objects\Nested\Floats::class,
            Objects\Nested\Integers::class,
            Objects\Nested\Strings::class,
            Objects\Nested\StringWrappers::class,
            Objects\Nested\WithHydrationStrategy\Json\Bools::class,
            Objects\Nested\WithHydrationStrategy\Json\Floats::class,
            Objects\Nested\WithHydrationStrategy\Json\Integers::class,
            Objects\Nested\WithHydrationStrategy\Json\Strings::class,
            Objects\Nested\WithHydrationStrategy\Merge\Bools::class,
            Objects\Nested\WithHydrationStrategy\Merge\Floats::class,
            Objects\Nested\WithHydrationStrategy\Merge\Integers::class,
            Objects\Nested\WithHydrationStrategy\Merge\Strings::class,
            Objects\Nested\WithHydrationStrategy\Nest\Bools::class,
            Objects\Nested\WithHydrationStrategy\Nest\Floats::class,
            Objects\Nested\WithHydrationStrategy\Nest\Integers::class,
            Objects\Nested\WithHydrationStrategy\Nest\Strings::class,
            Objects\Nested\WithHydrationStrategy\Passthrough\Bools::class,
            Objects\Nested\WithHydrationStrategy\Passthrough\Floats::class,
            Objects\Nested\WithHydrationStrategy\Passthrough\Integers::class,
            Objects\Nested\WithHydrationStrategy\Passthrough\Strings::class,
            Objects\StringWrappers::class,
            Scalars\Bools::class,
            Scalars\Floats::class,
            Scalars\Integers::class,
            Scalars\Strings::class,
            Scalars\Nullable\Bools::class,
            Scalars\Nullable\Floats::class,
            Scalars\Nullable\Integers::class,
            Scalars\Nullable\Strings::class,
            Scalars\Nullable\WithDefault\Bools::class,
            Scalars\Nullable\WithDefault\Floats::class,
            Scalars\Nullable\WithDefault\Integers::class,
            Scalars\Nullable\WithDefault\Strings::class,
            Scalars\Nullable\WithNullDefault\Bools::class,
            Scalars\Nullable\WithNullDefault\Floats::class,
            Scalars\Nullable\WithNullDefault\Integers::class,
            Scalars\Nullable\WithNullDefault\Strings::class,
            Scalars\WithDefault\Bools::class,
            Scalars\WithDefault\Floats::class,
            Scalars\WithDefault\Integers::class,
            Scalars\WithDefault\Strings::class,
        ];

        foreach ($fixtures as $fixture) {
            $cases = $fixture::getExampleObjects();
            foreach ($cases as $case => $example) {
                yield sprintf('%s => %s', $fixture, $case) => [
                    self::makeHydratorExist($fixture),
                    $example,
                ];
            }
        }
    }

    private static function makeHydratorExist(
        string $fixture,
        ?string $namespace = null
    ): object {
        $namespace ??= uniqid('Generated');

        $code = self::getBuilder()->build($fixture, $namespace);

        eval((string) $code);

        return new ($code->getHydratorClassName()->asString())(...array_map(
            fn($h) => self::makeHydratorExist($h, $namespace),
            $code->getSubHydrators(),
        ));
    }

    private static function getBuilder(): Builder
    {
        if (!isset(self::$builder)) {
            self::$builder = new Builder();
        }

        return self::$builder;
    }
}
