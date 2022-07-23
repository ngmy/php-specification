<?php

declare(strict_types=1);

namespace Ngmy\Specification;

use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

/**
 * OR specification, used to create a new specification that is the OR of two other specifications.
 *
 * @template T
 * @extends AbstractSpecification<T>
 */
class OrSpecification extends AbstractSpecification
{
    /**
     * Create a new OR specification based on two other spec.
     *
     * @param SpecificationInterface<T> $spec1 specification one
     * @param SpecificationInterface<T> $spec2 specification two
     */
    public function __construct(private SpecificationInterface $spec1, private SpecificationInterface $spec2)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return $this->spec1->isSatisfiedBy($candidate) || $this->spec2->isSatisfiedBy($candidate);
    }

    /**
     * {@inheritdoc}
     */
    public function applyToEloquent(EloquentBuilder $query): void
    {
        $query
            ->where(function (EloquentBuilder $query): void {
                $this->spec1->applyToEloquent($query);
            })
            ->orWhere(function (EloquentBuilder $query): void {
                $this->spec2->applyToEloquent($query);
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function applyToDoctrine(DoctrineQueryBuilder $queryBuilder): void
    {
        $entity = $queryBuilder->getRootEntities()[0];
        $alias = $queryBuilder->getRootAliases()[0];

        $entityManager = $queryBuilder->getEntityManager();

        $queryBuilder1 = $entityManager->createQueryBuilder();
        $queryBuilder1->from($entity, $alias);
        $queryBuilder2 = $entityManager->createQueryBuilder();
        $queryBuilder2->from($entity, $alias);

        $this->spec1->applyToDoctrine($queryBuilder1);
        $this->spec2->applyToDoctrine($queryBuilder2);

        /** @var Andx */
        $where1 = $queryBuilder1->getDQLPart('where');

        /** @var Andx */
        $where2 = $queryBuilder2->getDQLPart('where');

        $queryBuilder->andWhere(
            $queryBuilder->expr()->orX(
                $where1,
                $where2,
            )
        );
    }
}
