<?php

declare(strict_types=1);

namespace Atto\Hydrator\Tests\Functional;

use Atto\Hydrator\Builder;
use PHPUnit\Framework\TestCase;

final class FunctionalTest extends TestCase
{
    private Builder $sut;

    public function setUp(): void
    {
        $this->sut = new Builder();
    }

    public function testFixtures(string $classname): void
    {
        $this->sut->build($classname);
    }
}