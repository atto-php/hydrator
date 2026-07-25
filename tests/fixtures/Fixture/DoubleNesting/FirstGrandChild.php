<?php

namespace Atto\Hydrator\TestFixtures\Fixture\DoubleNesting;

class FirstGrandChild
{
    public function __construct(private string $testString)
    {

    }
}