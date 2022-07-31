<?php

declare(strict_types=1);

namespace Ngmy\Specification\Test\Stub\Specification;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Ngmy\Specification\AbstractSpecification;
use Ngmy\Specification\Support\DoctrineUtils;

/**
 * Popular user specification.
 *
 * @template T
 * @extends AbstractSpecification<T>
 */
class PopularUserSpecification extends AbstractSpecification
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
        $query->where('votes', '>', 100);
    }

    /**
     * {@inheritdoc}
     */
    public function applyToDoctrine(DoctrineQueryBuilder $queryBuilder): void
    {
        $queryBuilder->andWhere($queryBuilder->expr()->gt(
            DoctrineUtils::getRootAliasedColumnName($queryBuilder, 'votes'),
            DoctrineUtils::createUniqueNamedParameter($this, $queryBuilder, 100),
        ));
    }
}
