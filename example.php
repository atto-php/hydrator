<?php

enum Enummm: int
{
    case One = 1;
    case Two = 2;
    case Three = 3;
}

class ValueObject
{
    public function __construct(
        private string $property1,
        private int $property2
    ) {}
}

class Test
{
    public function __construct(
        private int $integer,
        private string $string,
        private \DateTimeImmutable $dateTime,
        #[\Atto\Hydrator\Attribute\SerializationStrategy(\Atto\Hydrator\Attribute\SerializationStrategyType::Json)]
        private array $arrayOfStrings,
        private Enummm $enummm,
        #[\Atto\Hydrator\Attribute\HydrationStrategy(\Atto\Hydrator\Attribute\HydrationStrategyType::Json)]
        private ValueObject $valueObject,
        #[\Atto\Hydrator\Attribute\HydrationStrategy(\Atto\Hydrator\Attribute\HydrationStrategyType::Nest)]
        private ValueObject $valueObject2
    ) {}
}

include './vendor/squizlabs/php_codesniffer/autoload.php';
include './vendor/autoload.php';

$builder = new \Atto\Hydrator\Builder();

$code = $builder->build(\Test::class);

$descriptorspec = array(
    0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
    1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
    2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
);

$cwd = '/tmp';
$env = array('some_option' => 'aeiou');

$process = proc_open(__DIR__ . '/vendor/bin/phpcbf --standard=psr12 -', $descriptorspec, $pipes, $cwd, $env);

if (is_resource($process)) {
    // $pipes now looks like this:
    // 0 => writeable handle connected to child stdin
    // 1 => readable handle connected to child stdout
    // Any error output will be appended to /tmp/error-output.txt

    fwrite($pipes[0], '<?php ' . $code);
    fclose($pipes[0]);

    $cleanCode = stream_get_contents($pipes[1]);
    file_put_contents(__DIR__ . '/TestHydratorMaster.php', $cleanCode);
    echo $cleanCode;
    fclose($pipes[1]);

    // It is important that you close any pipes before calling
    // proc_close in order to avoid a deadlock
    $return_value = proc_close($process);

    echo "command returned $return_value\n";
}