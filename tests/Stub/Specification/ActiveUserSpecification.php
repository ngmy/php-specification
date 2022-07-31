<?php

declare(strict_types=1);

namespace Ngmy\Specification\Test\Stub\Specification;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Ngmy\Specification\AbstractSpecification;
use Ngmy\Specification\Support\DoctrineUtils;

/**
 * Active user specification.
 *
 * @template T
 * @extends AbstractSpecification<T>
 */
class ActiveUserSpecification extends AbstractSpecification
{
    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function applyToEloquent(EloquentBuilder $query): void
    {
        $query->where('active', 1);
    }

    /**
     * {@inheritdoc}
     */
    public function applyToDoctrine(DoctrineQueryBuilder $queryBuilder): void
    {
        $queryBuilder->andWhere($queryBuilder->expr()->eq(
            DoctrineUtils::getRootAliasedColumnName($queryBuilder, 'active'),
            DoctrineUtils::createUniqueNamedParameter($this, $queryBuilder, 1),
        ));
    }
}
