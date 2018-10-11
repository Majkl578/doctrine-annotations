<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata;

use Doctrine\Annotations\Metadata\InvalidPropertyValue;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Annotations\Metadata\Type\Type;
use Doctrine\Tests\Annotations\Metadata\Type\PropertyMetadataMother;
use PHPUnit\Framework\TestCase;

class PropertyMetadataTest extends TestCase
{
    /**
     * @param mixed $value
     *
     * @dataProvider validTypeValidationExamples
     */
    public function testValidatesValuesMatchingType(Type $type, $value) : void
    {
        $metadata = PropertyMetadataMother::withType($type);

        $result = $metadata->validateValue($value);

        $this->assertTrue($result);
    }

    /**
     * @return mixed[]
     */
    public function validTypeValidationExamples() : iterable
    {
        yield 'valid string' => [
            new StringType(),
            'foo',
        ];
    }

    /**
     * @param mixed $value
     *
     * @dataProvider invalidTypeValidationExamples
     */
    public function testNotValidatesValuesNotMatchingTypeAndThrows(Type $type, $value) : void
    {
        $metadata = PropertyMetadataMother::withType($type);

        $this->expectException(InvalidPropertyValue::class);

        $metadata->validateValue($value);
    }

    /**
     * @return mixed[]
     */
    public function invalidTypeValidationExamples() : iterable
    {
        yield 'value not matching property type' => [
            new StringType(),
            42,
        ];
    }
}
