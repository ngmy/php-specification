<?php

declare(strict_types=1);

namespace Ngmy\Specification;

use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

/**
 * NOT decorator, used to create a new specification that is the inverse (NOT) of the given spec.
 *
 * @template T
 * @extends AbstractSpecification<T>
 */
class NotSpecification extends AbstractSpecification
{
    /**
     * Create a new NOT specification based on another spec.
     *
     * @param SpecificationInterface<T> $spec1 Specification instance to not.
     */
    public function __construct(private SpecificationInterface $spec1)
    {
    }

    /**
     * @inheritdoc
     */
    public function isSatisfiedBy($candidate): bool
    {
        return !$this->spec1->isSatisfiedBy($candidate);
    }

    /**
     * @inheritdoc
     */
    public function applyToEloquent(EloquentBuilder $query): void
    {
        $query->whereNot(function (EloquentBuilder $query): void {
            $this->spec1->applyToEloquent($query);
        });
    }

    /**
     * @inheritdoc
     */
    public function applyToDoctrine(DoctrineQueryBuilder $queryBuilder): void
    {
        $this->spec1->applyToDoctrine($queryBuilder);
        /** @var Andx */
        $where = $queryBuilder->getDQLPart('where');
        $queryBuilder->where($queryBuilder->expr()->not($where));
    }
}
