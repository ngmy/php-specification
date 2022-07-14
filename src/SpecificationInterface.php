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
 * @template T
 */
interface SpecificationInterface
{
    /**
     * Check if the `$candidate` argument is satisfied by the specification.
     *
     * @param T $candidate Object to test.
     * @return bool `true` if the `$candidate` argument satisfies the specification.
     */
    public function isSatisfiedBy($candidate): bool;

    /**
     * Create a new specification that is the AND operation of this specification and another specification.
     *
     * @param SpecificationInterface<T> $specification Specification to AND.
     * @return SpecificationInterface<T> A new specification.
     */
    public function and(SpecificationInterface $specification): SpecificationInterface;

    /**
     * Create a new specification that is the OR operation of this specification and another specification.
     *
     * @param SpecificationInterface<T> $specification Specification to OR.
     * @return SpecificationInterface<T> A new specification.
     */
    public function or(SpecificationInterface $specification): SpecificationInterface;

    /**
     * Create a new specification that is the NOT operation of this specification.
     *
     * @return SpecificationInterface<T> A new specification.
     */
    public function not(): SpecificationInterface;

    /**
     * Apply this specification to Eloquent ORM.
     *
     * @param EloquentBuilder|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation $query Eloquent ORM.
     * @return void
     */
    public function applyToEloquent(EloquentBuilder $query): void;

    /**
     * Apply this specification to Doctrine ORM.
     *
     * @param DoctrineQueryBuilder $queryBuilder Doctrine ORM.
     * @return void
     */
    public function applyToDoctrine(DoctrineQueryBuilder $queryBuilder): void;
}
