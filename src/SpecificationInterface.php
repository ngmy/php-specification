<?php

declare(strict_types=1);

namespace Ngmy\Specification;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

/**
 * Specification interface.
 *
 * Use the `AbstractSpecification` class as base for creating specifications,
 * and only the `isSatisfiedBy` method must be implemented.
 *
 * @template T of object
 */
interface SpecificationInterface
{
    /**
     * Check if the `$candidate` argument is satisfied by the specification.
     *
     * @param T $candidate object to test
     *
     * @return bool `true` if the `$candidate` argument satisfies the specification
     */
    public function isSatisfiedBy(object $candidate): bool;

    /**
     * Create a new specification that is always true.
     *
     * @return SpecificationInterface<T> a new specification
     */
    public static function true(): SpecificationInterface;

    /**
     * Create a new specification that is always false.
     *
     * @return SpecificationInterface<T> a new specification
     */
    public static function false(): SpecificationInterface;

    /**
     * Create a new specification that is the AND operation of this specification and another specification.
     *
     * @param SpecificationInterface<T> $specification specification to AND
     *
     * @return SpecificationInterface<T> a new specification
     */
    public function and(SpecificationInterface $specification): SpecificationInterface;

    /**
     * Create a new specification that is the OR operation of this specification and another specification.
     *
     * @param SpecificationInterface<T> $specification specification to OR
     *
     * @return SpecificationInterface<T> a new specification
     */
    public function or(SpecificationInterface $specification): SpecificationInterface;

    /**
     * Create a new specification that is the NOT operation of this specification.
     *
     * @return SpecificationInterface<T> a new specification
     */
    public function not(): SpecificationInterface;

    /**
     * Apply this specification to Eloquent ORM.
     *
     * @param EloquentBuilder|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation $query eloquent ORM
     */
    public function applyToEloquent(EloquentBuilder $query): void;

    /**
     * Apply this specification to Doctrine ORM.
     *
     * @param DoctrineQueryBuilder $queryBuilder doctrine ORM
     */
    public function applyToDoctrine(DoctrineQueryBuilder $queryBuilder): void;
}
