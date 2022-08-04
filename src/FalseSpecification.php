<?php

declare(strict_types=1);

namespace Ngmy\Specification;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

/**
 * False specification.
 *
 * @template T of object
 * @extends AbstractSpecification<T>
 */
class FalseSpecification extends AbstractSpecification
{
    /** @var self<T> Singleton instance of this class. */
    private static FalseSpecification $instance;

    private function __construct()
    {
    }

    /**
     * Return the singleton instance of this class.
     *
     * @return self<T> singleton instance of this class
     */
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            /** @var self<T> */
            $instance = new self();
            self::$instance = $instance;
        }

        return self::$instance;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy(object $candidate): bool
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
