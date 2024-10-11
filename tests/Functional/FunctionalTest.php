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
            Arrays\Flat\Scalars::class,
            Arrays\Nested\Scalars::class,
            Objects\Flat\DateTimes::class,
            Objects\Nested\Default\Strings::class,
            Objects\Nested\Merge\Strings::class,
            Objects\Nested\Nest\Strings::class,
            Scalars\Bools::class,
            Scalars\Floats::class,
            Scalars\Integers::class,
            Scalars\Strings::class,
            Scalars\BoolsDefault::class,
            Scalars\FloatsDefault::class,
            Scalars\IntegersDefault::class,
            Scalars\StringsDefault::class,
            // Scalars\Nullable\Bools::class,
            // Scalars\Nullable\Floats::class,
            // Scalars\Nullable\Integers::class,
            // Scalars\Nullable\Strings::class,
            // Scalars\Nullable\BoolsDefaultNull::class,
            // Scalars\Nullable\FloatsDefaultNull::class,
            // Scalars\Nullable\IntegersDefaultNull::class,
            // Scalars\Nullable\StringsDefaultNull::class,
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
