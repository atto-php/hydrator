<?php

namespace Atto\Hydrator\TestFixtures\Fixture\DoubleNesting;

class FirstChild
{
    public function __construct(
        private FirstGrandChild $firstGrandChild,
    ){
    }
}