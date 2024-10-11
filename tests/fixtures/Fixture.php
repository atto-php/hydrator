<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures;

interface Fixture
{

    /** @return Fixture[] */
    public static function getExampleObjects(): array;

    public function getExpectedArray(): array;
}
