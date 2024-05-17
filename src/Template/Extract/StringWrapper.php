<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

use Atto\Hydrator\Attribute\SerializationStrategyType;

final class StringWrapper
{
    const EXTRACT_FORMAT = '(string) %s';

    use BasicExtract;
}