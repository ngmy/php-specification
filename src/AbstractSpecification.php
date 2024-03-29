<?php

declare(strict_types=1);

namespace Ngmy\Specification;

use BadMethodCallException;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

/**
 * Abstract base implementation of the `SpecificationInterface` interface with default
 * implementations for the `and`, `or` and `not` methods.
 *
 * @template T of object
 * @implements SpecificationInterface<T>
 */
abstract class AbstractSpecification implements SpecificationInterface
{
    use DoctrineTrait;

    /**
     * {@inheritdoc}
     */
    public static function true(): SpecificationInterface
    {
        /** @var TrueSpecification<T> */
        $true = TrueSpecification::getInstance();

        return $true;
    }

    /**
     * {@inheritdoc}
     */
    public static function false(): SpecificationInterface
    {
        /** @var FalseSpecification<T> */
        $false = FalseSpecification::getInstance();

        return $false;
    }

    /**
     * {@inheritdoc}
     */
    public function and(SpecificationInterface $specification): SpecificationInterface
    {
        return new AndSpecification($this, $specification);
    }

    /**
     * {@inheritdoc}
     */
    public function or(SpecificationInterface $specification): SpecificationInterface
    {
        return new OrSpecification($this, $specification);
    }

    /**
     * {@inheritdoc}
     */
    public function not(): SpecificationInterface
    {
        return new NotSpecification($this);
    }

    /**
     * {@inheritdoc}
     */
    public function applyToEloquent(EloquentBuilder $query): void
    {
        throw new BadMethodCallException('Please overload and implement this method.');
    }

    /**
     * {@inheritdoc}
     */
    public function applyToDoctrine(DoctrineQueryBuilder $queryBuilder): void
    {
        throw new BadMethodCallException('Please overload and implement this method.');
    }
}
