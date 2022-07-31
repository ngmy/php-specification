<?php

declare(strict_types=1);

namespace Ngmy\Specification;

use Doctrine\Common\Collections\ArrayCollection as DoctrineArrayCollection;
use Doctrine\ORM\Query\Expr\Andx as DoctrineAndx;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

/**
 * AND specification, used to create a new specification that is the AND of two other specifications.
 *
 * @template T
 * @extends AbstractSpecification<T>
 */
class AndSpecification extends AbstractSpecification
{
    /**
     * Create a new AND specification based on two other spec.
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
        return $this->spec1->isSatisfiedBy($candidate) && $this->spec2->isSatisfiedBy($candidate);
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
            ->where(function (EloquentBuilder $query): void {
                $this->spec2->applyToEloquent($query);
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function applyToDoctrine(DoctrineQueryBuilder $queryBuilder): void
    {
        $entities = $queryBuilder->getRootEntities();
        $aliases = $queryBuilder->getRootAliases();

        $entityManager = $queryBuilder->getEntityManager();

        $queryBuilder1 = $entityManager->createQueryBuilder();
        $queryBuilder1->from($entities[0], $aliases[0]);
        $this->spec1->applyToDoctrine($queryBuilder1);

        $queryBuilder2 = $entityManager->createQueryBuilder();
        $queryBuilder2->from($entities[0], $aliases[0]);
        $this->spec2->applyToDoctrine($queryBuilder2);

        /** @var DoctrineAndx */
        $where1 = $queryBuilder1->getDQLPart('where');

        /** @var DoctrineAndx */
        $where2 = $queryBuilder2->getDQLPart('where');

        $queryBuilder->andWhere(
            $queryBuilder->expr()->andX(
                $where1,
                $where2,
            )
        );

        $parameters = $queryBuilder->getParameters();
        $parameters1 = $queryBuilder1->getParameters();
        $parameters2 = $queryBuilder2->getParameters();
        $parameters = new DoctrineArrayCollection(array_merge(
            $parameters->toArray(),
            $parameters1->toArray(),
            $parameters2->toArray(),
        ));
        $queryBuilder->setParameters($parameters);
    }
}
