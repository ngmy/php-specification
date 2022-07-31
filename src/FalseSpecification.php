<?php

declare(strict_types=1);

namespace Ngmy\Specification;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

/**
 * False specification.
 *
 * @extends AbstractSpecification<mixed>
 */
class FalseSpecification extends AbstractSpecification
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
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function applyToEloquent(EloquentBuilder $query): void
    {
        $query->whereRaw('1 = 0');
    }

    /**
     * {@inheritdoc}
     */
    public function applyToDoctrine(DoctrineQueryBuilder $queryBuilder): void
    {
        $queryBuilder->andWhere('1 = 0');
    }
}
