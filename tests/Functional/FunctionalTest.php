<?php

declare(strict_types=1);

namespace Atto\Hydrator\Tests\Functional;

use Atto\Hydrator\Builder;
use Atto\Hydrator\TestFixtures\BasicScalars;
use Atto\Hydrator\TestFixtures\Fixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FunctionalTest extends TestCase
{
    private Builder $sut;

    public function setUp(): void
    {
        $this->sut = new Builder();
    }

    #[DataProvider('provideClassNames')]
    /** @param class-string<Fixture> $classname */
    public function testFixtures(string $classname): void
    {
        $code = $this->sut->build($classname);
        $testable = $code->getHydratorClassName()->asString();

        eval((string) $code);

        $sut = new $testable();
        $exampleObject = $classname::getExampleObject();
        $expectedArray = $classname::getExpectedArray();

        self::assertEquals($expectedArray, $sut->extract($exampleObject));
        self::assertEquals($exampleObject, $sut->create($expectedArray));
    }

    public static function provideClassNames(): array
    {
        return [
            [BasicScalars::class]
        ];
    }
}