<?php

declare(strict_types=1);

namespace Ngmy\Specification;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

/**
 * True specification.
 *
 * @extends AbstractSpecification<mixed>
 */
class TrueSpecification extends AbstractSpecification
{
    /** @var null|self Singleton instance of this class. */
    private static $instance;

    private function __construct()
    {
    }

    /**
     * Return the singleton instance of this class.
     *
     * @return self singleton instance of this class
     */
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return true;
    }

    /**
     * @param SpecificationInterface<mixed> $specification
     */
    public function and(SpecificationInterface $specification): SpecificationInterface
    {
        return new AndSpecification($this, $specification);
    }

    /**
     * @param SpecificationInterface<mixed> $specification
     */
    public function or(SpecificationInterface $specification): SpecificationInterface
    {
        return new OrSpecification($this, $specification);
    }

    /**
     * {@inheritdoc}
     */
    public function applyToEloquent(EloquentBuilder $query): void
    {
        $query->whereRaw('1 = 1');
    }

    /**
     * {@inheritdoc}
     */
    public function applyToDoctrine(DoctrineQueryBuilder $queryBuilder): void
    {
        $queryBuilder->andWhere('1 = 1');
    }
}
