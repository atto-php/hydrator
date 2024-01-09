<?php

declare(strict_types=1);

namespace Atto\Hydrator\Attribute;

enum SerializationStrategyType: string
{
    case Json = 'json';
    case CommaDelimited = 'comma-delimited';
    case PipeDelimited = 'pipe-delimited';
}

