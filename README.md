# Atto Hydrator

The hydrator is designed to _quickly_ serialise (extract) and deserialise (hydrate) complex objects.

It works out of the box on simple data structures.
For anything else, [behaviour can be defined](#manually-defined-behaviour) to meet your needs.

## Default Behaviour

Your DTO's properties are extracted into an array.

The value of each property is converted into a JSON string.

Arrays must [specify a subtype](#subtype).
Arrays ordered integer keys (lists) are extracted into a _nested_ JSON array.
Arrays with specified keys are extracted into a _nested_ JSON object.

Object are extracted into a _nested_ JSON object.

If the default behaviour does not suit your needs; you can [manually define alternate behaviour](#manually-defined-behaviour)

## Manually Defined Behaviour

Behaviour can be defined using _attributes_ from the `Atto\Hydrator\Attribute` namespace.

### Serialization Strategy

Serialization strategies are only useful for arrays of data.
They will have no impact on scalars.
They cannot be used on objects.

The default strategy is to serialize as JSON.

```php
    // This is the default behaviour if omitted.
    #[SerializationStrategy(SerializationStrategyType::JSON)]
    private array $myList;
```

It may also be serialized to a comma-delimited or pipe-delimited list.

```php
    #[SerializationStrategy(SerializationStrategyType::CommaDelimited)]
    private array $myCommaDelimitedList;
    #[SerializationStrategy(SerializationStrategyType::PipeDelimited)]
    private array $myPipeDelimitedList;
```

CommaDelimited and PipeDelimited serialisation will:

- Only work for lists of simple data.
- Will lose your array keys.

### Hydration Strategy

#### Json

Json will json encode your property before the entire object is json encoded.

```php
class MyObject
{
    #[HydrationStrategy(HydrationStrategyType::Json)]
    private MyNestedObject $myNestedObject;
}

class MyNestedObject
{
    private int $myInt = 1;
}
```

The hydrator will extract the following output:

```php
['myNestedObject' => '{"myInt":1}']
```

#### Merge

Merge can only be used for a single level.

If you specify Merge on an object contains a nested object property.
The nested object's properties are merged into the original object, as if it were its own properties.

```php
class MyObject
{
    #[HydrationStrategy(HydrationStrategyType::Merge)]
    private MyNestedObject $myNestedObject;
}

class MyNestedObject
{
    private int $myInt = 1;
}
```

The hydrator will extract the following output:

```php
    ['myNestedObject_myInt' => 1]
```

If your property names include underscores, undefined behaviour MAY occur.
Stick to _camelCase_ property names for predictable behaviour.

#### Nest

Nest is the default behaviour of any property [except DateTimes, Enums and Scalars](#datetime-enum--scalar).

You should never need to specify it explicitly.

```php
class MyObject
{
    #[HydrationStrategy(HydrationStrategyType::Nest)]
    private MyNestedObject $myNestedObject;
}

class MyNestedObject
{
    private int $myInt = 1;
}
```

The hydrator will extract the following output:

```php
    [
        'myNestedObject' => ['myInt' => 1],
    ]
```

#### Passthrough

Passthrough will leave your property pass through untouched.

If Passthrough is used, it should be used at every level above it as well.

#### StringWrapper

A StringWrapper is an object which; takes a single string argument to `__construct` and returns it with `__toString()`.

i.e.

```php
class MyStringWrapper
{
    public function __construct(
        private string $myString = 'Hello, World!',
    ) {
    }

    public function __toString()
    {
        return $this->myString;
    }
}
```

For objects like this, we can serialise them as their constructor argument, a simple string.

```json
"Hello, World!"
```

#### DateTime, Enum & Scalar

Datetimes, Enums and Scalars have their own default strategies.

They do not need to be specified, but they can be overriden if you choose.

### Subtype

For an array, a subtype must be specified.

```php
    #[Subtype(\DateTime::class)]
    private array $listOfDateTimes
```

**Arrays must only contain items of that subtype**.

This is because, serialised data does not contain information of its type.
The hydrator uses this `Subtype` attribute to determine what to hydrate each item as.
