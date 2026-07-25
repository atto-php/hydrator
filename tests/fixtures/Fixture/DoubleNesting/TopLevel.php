<?php

namespace Atto\Hydrator\TestFixtures\Fixture\DoubleNesting;

use Atto\Hydrator\TestFixtures\Fixture;

class TopLevel implements Fixture
{
    public function __construct(
        private FirstChild $firstChild,
        private ?FirstChild $secondChild,
    )
    {}

    public static function getExampleObjects(): array
    {
        return [
            'double_nesting_merge' => new self(
                new FirstChild(new FirstGrandChild('string')),
                new FirstChild(new FirstGrandChild('differentString'))
            ),
        ];
    }

    public function getExpectedObject(): Fixture
    {
        return new self(
            new FirstChild(new FirstGrandChild('string')),
            new FirstChild(new FirstGrandChild('differentString'))
        );
    }

    public function getExpectedArray(): array
    {
        return [
            'secondChild' => true,
            'firstChild_firstGrandChild_testString' => 'string',
            'secondChild_firstGrandChild_testString' => 'differentString',
        ];
    }

}