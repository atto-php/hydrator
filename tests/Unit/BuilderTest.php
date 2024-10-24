<?php

declare(strict_types=1);

namespace Atto\Hydrator\Tests\Unit;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\Builder;

use Atto\Hydrator\Exception\AttributeNotApplicable;
use Atto\Hydrator\TestFixtures\Mocks\Enums\StringDummy;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Builder::class)]
#[CoversClass(AttributeNotApplicable::class)]
#[UsesClass(\Atto\Hydrator\Attribute\Subtype::class)]
#[UsesClass(\Atto\Hydrator\ClassName::class)]
#[UsesClass(\Atto\Hydrator\Template\Closure::class)]
#[UsesClass(\Atto\Hydrator\Template\HydratorClass::class)]
final class BuilderTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    #[DataProvider('provideNonApplicableSubtypes')]
    public function itThrowsOnNonApplicableSubtypes(
        object $object,
        string $propertyName,
        string $propertyType,
    ): void {
        $sut = new Builder();

        self::expectExceptionObject(
            AttributeNotApplicable::subtype($propertyType, $propertyName)
        );

        $sut->build($object::class);
    }

    #[Test]
    #[DataProvider('provideNonApplicableSerialisationStrategies')]
    public function itThrowsOnNonApplicableSerialisationStrategies(
        object $object,
        string $propertyName,
        string $propertyType,
    ): void {
        $sut = new Builder();

        self::expectExceptionObject(
            AttributeNotApplicable::serialisationStrategy($propertyType, $propertyName)
        );

        $sut->build($object::class);
    }

    /** @return \Generator<array{ 0:object, 1:string, 2:string }> */
    public static function provideNonApplicableSubtypes(): \Generator
    {
        yield 'bool property' => [
            new class () {
                #[Subtype('bool')]
                private bool $boolProperty;
            },
            'boolProperty',
            'bool',
        ];

        yield 'float property' => [
            new class () {
                #[Subtype('float')]
                private float $floatProperty;
            },
            'floatProperty',
            'float',
        ];

        yield 'int property' => [
            new class () {
                #[Subtype('int')]
                private int $intProperty;
            },
            'intProperty',
            'int',
        ];

        yield 'string property' => [
          new class () {
            #[Subtype('string')]
            private string $stringProperty;
          },
            'stringProperty',
            'string',
        ];

        yield 'object property' => [
            new class () {
                #[Subtype('string')]
                private object $objectProperty;
            },
            'objectProperty',
            'object',
        ];

        yield 'StringBackedEnum property' => [
            new class () {
                #[Subtype('string')]
                private StringDummy $enumProperty;
            },
            'enumProperty',
            StringDummy::class,
        ];
    }

    /** @return \Generator<array{ 0:object, 1:string, 2:string }> */
    public static function provideNonApplicableSerialisationStrategies(): \Generator {
        yield 'bool property' => [
            new class () {
                #[SerializationStrategy(SerializationStrategyType::Json)]
                private bool $boolProperty;
            },
            'boolProperty',
            'bool',
        ];

        yield 'float property' => [
            new class () {
                #[SerializationStrategy(SerializationStrategyType::Json)]
                private float $floatProperty;
            },
            'floatProperty',
            'float',
        ];

        yield 'int property' => [
            new class () {
                #[SerializationStrategy(SerializationStrategyType::Json)]
                private int $intProperty;
            },
            'intProperty',
            'int',
        ];

        yield 'string property' => [
            new class () {
                #[SerializationStrategy(SerializationStrategyType::Json)]
                private string $stringProperty;
            },
            'stringProperty',
            'string',
        ];

        yield 'object property' => [
            new class () {
                #[SerializationStrategy(SerializationStrategyType::Json)]
                private object $objectProperty;
            },
            'objectProperty',
            'object',
        ];

        yield 'StringBackedEnum property' => [
            new class () {
                #[SerializationStrategy(SerializationStrategyType::Json)]
                private StringDummy $enumProperty;
            },
            'enumProperty',
            StringDummy::class,
        ];
    }
}
