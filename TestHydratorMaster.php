<?php

namespace Generated;

final class TestHydrator
{
    public const PROPERTIES = [
        'integer',
        'string',
        'dateTime',
        'arrayOfStrings',
        'enummm',
        'valueObject',
        'valueObject2'
    ];

    private readonly array $hydrateMethods;
    private readonly array $extractMethods;
    private readonly \Doctrine\Instantiator\Instantiator $instantiator;

    public function __construct(
        \ValueObjectHydrator $p1,
    ) {
            $hydrate[\ValueObject::class] = $p1->hydrate(...);
        $extract[\ValueObject::class] = $p1->extract(...);
        $merge[\ValueObject::class] = static function ($prefix, &$data, $object) use ($p1) {
            $extractedData = $p1->extract($object);
            foreach ($extractedData as $key => $value) {
                $data[$prefix . '_' . $key] = $value;
            }
        };
        $unmerge[\ValueObject::class] = static function ($prefix, &$data) use ($p1) {
            $hydrateData = [];
            foreach ($p1::PROPERTIES as $key) {
                $dataKey = $prefix . '_' . $key;
                if (array_key_exists($dataKey, $data)) {
                    $hydrateData[$key] = $data[$dataKey];
                }
            }

            return $p1->create($hydrateData);
        };
        $this->hydrateMethods[0] = \Closure::bind(static function ($object, &$values) use (&$extract, &$hydrate, &$merge, &$unmerge) {
            if (
                isset($values['integer']) ||
                isset($object->integer) &&
                array_key_exists('integer', $values)
            ) {
                $object->integer = $values['integer'];
            }
            if (
                isset($values['string']) ||
                isset($object->string) &&
                array_key_exists('string', $values)
            ) {
                $object->string = $values['string'];
            }
            if (
                isset($values['dateTime']) ||
                isset($object->dateTime) &&
                array_key_exists('dateTime', $values)
            ) {
                $object->dateTime = new \DateTimeImmutable($values['dateTime']);
            }
            if (
                isset($values['arrayOfStrings']) ||
                isset($object->arrayOfStrings) &&
                array_key_exists('arrayOfStrings', $values)
            ) {
                $object->arrayOfStrings = array_map(fn($value) => $value, json_decode($values['arrayOfStrings'], true));
            }
            if (
                isset($values['enummm']) ||
                isset($object->enummm) &&
                array_key_exists('enummm', $values)
            ) {
                $object->enummm = \Enummm::tryFrom($values['enummm']);
            }
            if (
                isset($values['valueObject']) ||
                isset($object->valueObject) &&
                array_key_exists('valueObject', $values)
            ) {
                $object->valueObject = $hydrate[\ValueObject::class](json_decode($values['valueObject']));
            }
            if (
                isset($values['valueObject2']) ||
                isset($object->valueObject2) &&
                array_key_exists('valueObject2', $values)
            ) {
                $object->valueObject2 = $unmerge[\ValueObject::class]('valueObject2', $values['valueObject2']);
            }
        }, null, \Test::class);
        $this->extractMethods[0] = \Closure::bind(static function ($object, &$values) use (&$extract, &$hydrate, &$merge, &$unmerge) {
            $values['integer'] = $object->integer;
            $values['string'] = $object->string;
            $values['dateTime'] = $object->dateTime->format(\DATE_ATOM);
            $values['arrayOfStrings'] = json_encode(array_map(fn($value) => $value, $object->arrayOfStrings));
            $values['enummm'] = $object->enummm->value;
            $values['valueObject'] = json_encode($extract[\ValueObject::class]($object->valueObject));
            $values['valueObject2'] = $merge[\ValueObject::class]('valueObject2', $values, $object->valueObject2);
        }, null, \Test::class);

        $this->instantiator = new \Doctrine\Instantiator\Instantiator();
    }

    public function create(array &$data): object
    {
        $object = $this->instantiator->instantiate(\Test::class);
        $this->hydrate($data, $object);
        return $object;
    }

    public function hydrate(array &$data, object $object): void
    {
        $this->hydrateMethods[0]->__invoke($object, $data);
    }

    public function extract(object $object): array
    {
        $data = [];
        $this->extractMethods[0]->__invoke($object, $data);
        return $data;
    }
}
