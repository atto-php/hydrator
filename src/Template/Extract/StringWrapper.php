<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

final class StringWrapper
{
    const EXTRACT_FORMAT = '(string) %s';

    use BasicExtract;
}
