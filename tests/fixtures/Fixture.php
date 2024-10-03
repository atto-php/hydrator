<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures;

interface Fixture
{
    public static function getExampleObject(): Fixture;

    public static function getExpectedArray(): array;
}