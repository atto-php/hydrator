<?php

declare(strict_types=1);

namespace Atto\Hydrator\Tests\Functional;

use Atto\Hydrator\Builder;
use Atto\Hydrator\TestFixtures\Fixture;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class FunctionalTest extends TestCase
{
    private const FIXTURES = [
        // @TODO Fix subtypes in order to support enums
        Fixture\WithArrays\OfEnums\IntBacked::class,
        Fixture\WithArrays\OfEnums\StringBacked::class,
        Fixture\WithArrays\OfObjects\WithScalars\Bools::class,
        Fixture\WithArrays\OfScalars\Bools::class,
        Fixture\WithArrays\OfScalars\Floats::class,
        Fixture\WithArrays\OfScalars\Integers::class,
        Fixture\WithArrays\OfScalars\Strings::class,
        Fixture\WithDateTimes::class,
        Fixture\WithEnums\IntBacked::class,
        Fixture\WithEnums\StringBacked::class,
        Fixture\WithObjects\WithDateTimes::class,
        Fixture\WithObjects\WithScalars\Bools::class,
        Fixture\WithObjects\WithScalars\Floats::class,
        Fixture\WithObjects\WithScalars\Integers::class,
        Fixture\WithObjects\WithScalars\Strings::class,
        Fixture\WithObjects\WithStringWrappers::class,
        Fixture\WithScalars\Bools::class,
        Fixture\WithScalars\Floats::class,
        Fixture\WithScalars\Integers::class,
        Fixture\WithScalars\Strings::class,
        Fixture\WithStringWrappers::class,
    ];

    private static Builder $builder;
    private static array $generatedHydrators = [];

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
            $hydrator->extract($fixture),
            // self::$generatedHydrators[$fixture::class]
        );
    }

    #[Test]
    #[DataProvider('provideFixtures')]
    #[TestDox('It creates and hydrates an object, from an array')]
    public function itCreatesFromArray(object $hydrator, Fixture $fixture): void
    {
        self::assertEquals(
            $fixture->getExpectedObject(),
            $hydrator->create($fixture->getExpectedArray()),
            // self::$generatedHydrators[$fixture::class]
        );
    }
    //
    // #[Test]
    // #[DataProvider('provideFixtures')]
    // #[TestDox('"extract" and "create" form an idempotent operation')]
    // public function itIsIdempotent(object $hydrator, Fixture $fixture): void
    // {
    //     $object = $fixture;
    //     for ($i = 0; $i < 3; $i++) {
    //         $array = $hydrator->extract($object);
    //         $object = $hydrator->create($array);
    //     }
    //
    //     self::assertEquals(
    //         $fixture->getExpectedObject(),
    //         $object,
    //         // self::$generatedHydrators[$fixture::class]
    //     );
    //     self::assertEquals(
    //         $fixture->getExpectedArray(),
    //         $array,
    //         // self::$generatedHydrators[$fixture::class],
    //     );
    // }

    #[Test]
    public function itCreatesAHydrator(): void
    {
        foreach (self::FIXTURES as $fixture) {
            self::makeHydratorExist($fixture);
        }

        self::assertTrue(true);
    }

    public static function provideFixtures(): Generator
    {
        foreach (self::FIXTURES as $fixture) {
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

        self::$generatedHydrators[$fixture] = (string) $code;

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
