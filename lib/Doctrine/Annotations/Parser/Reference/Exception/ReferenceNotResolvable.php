<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Reference\Exception;

use Doctrine\Annotations\Parser\Ast\Reference;
use RuntimeException;
use function sprintf;

final class ReferenceNotResolvable extends RuntimeException implements ReferenceException
{
    public static function new(Reference $reference) : self
    {
        return new self(
            sprintf(
                '"%s%s" could not be resolved.',
                $reference->isFullyQualified() ? '\\' : '',
                $reference->getIdentifier()
            )
        );
    }

    public static function unknownImport(Reference $reference) : self
    {
        return new self(
            sprintf(
                '"%s%s" could not be resolved because referenced class was not imported.',
                $reference->isFullyQualified() ? '\\' : '',
                $reference->getIdentifier()
            )
        );
    }

    public static function unknownAlias(Reference $reference) : self
    {
        return new self(
            sprintf(
                '"%s%s" could not be resolved because referenced alias was not imported.',
                $reference->isFullyQualified() ? '\\' : '',
                $reference->getIdentifier()
            )
        );
    }
}
