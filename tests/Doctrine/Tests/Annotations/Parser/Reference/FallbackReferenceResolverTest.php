<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Parser\Reference;

use Doctrine\Annotations\Annotation\Target;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Reference\FallbackReferenceResolver;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Tests\Annotations\Parser\ScopeMother;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;
use stdClass;

class FallbackReferenceResolverTest extends TestCase
{
    /** @var FallbackReferenceResolver */
    private $resolver;

    public function setUp() : void
    {
        $this->resolver = new FallbackReferenceResolver();
    }

    /**
     * @dataProvider examples
     */
    public function testResolvesExamples(Reference $reference, Scope $scope, string $expected) : void
    {
        $result = $this->resolver->resolve($reference, $scope);

        $this->assertSame($expected, $result);
    }

    /**
     * @return mixed[]
     */
    public function examples() : iterable
    {
        yield 'true FCQN' => [
            new Reference(self::class, true),
            ScopeMother::example(),
            self::class,
        ];

        yield 'random string marked as FCQN' => [
            new Reference('foo', true),
            ScopeMother::example(),
            'foo',
        ];

        yield 'fetched from imports' => [
            new Reference('foo', false),
            ScopeMother::withImports([
                'foo' => Target::class,
            ]),
            Target::class,
        ];

        yield 'of subject that cannot be referenced with namespace' => [
            new Reference('foo', false),
            ScopeMother::withSubject(new ReflectionProperty(Target::class, 'value')),
            'foo',
        ];

        yield 'global class' => [
            new Reference('foo', false),
            ScopeMother::withSubject(new ReflectionClass(stdClass::class)),
            'foo',
        ];

        $targetReflection = new ReflectionClass(Target::class);

        yield 'fallback' => [
            new Reference('foo', false),
            ScopeMother::withSubject($targetReflection),
            $targetReflection->getNamespaceName() . '\\foo',
        ];
    }
}
