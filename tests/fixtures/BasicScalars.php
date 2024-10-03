<?php

declare(strict_types=1);

namespace Atto\Hydrator\TestFixtures;

final class BasicScalars implements Fixture
{
    public function __construct(
        private int $integer,
        private string $string,
        private bool $bool,
        private float $float,
    ) {
    }

    public static function getExampleObject(): Fixture
    {
        return new BasicScalars(1, 'string', true, 1.0);
    }

    public static function getExpectedArray(): array
    {
        return [
            'integer' => 1,
            'string' => 'string',
            'bool' => true,
            'float' => 1.0,
        ];
    }
}
