<?php

declare(strict_types=1);

namespace Ngmy\Specification;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

/**
 * True specification.
 *
 * @template T of object
 * @extends AbstractSpecification<T>
 */
class TrueSpecification extends AbstractSpecification
{
    /** @var self<T> Singleton instance of this class. */
    private static TrueSpecification $instance;

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
        return true;
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
