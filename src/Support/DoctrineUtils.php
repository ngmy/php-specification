<?php

declare(strict_types=1);

namespace Ngmy\Specification\Support;

use Doctrine\ORM\QueryBuilder;
use Ngmy\Specification\SpecificationInterface;

/**
 * Bunch of utility methods for Doctrine.
 */
class DoctrineUtils
{
    /**
     * Return a aliased column name.
     *
     * @param QueryBuilder $queryBuilder query builder
     * @param string       $columnName   column name
     *
     * @return string aliased column name
     */
    public static function getAliasedColumnName(QueryBuilder $queryBuilder, string $columnName): string
    {
        $aliases = $queryBuilder->getRootAliases();

        return sprintf('%s.%s', $aliases[0], $columnName);
    }

    /**
     * Create a new unique named parameter from `$specification`, and bind the `$value` to the `$queryBuilder`.
     *
     * @template T
     *
     * @param SpecificationInterface<T> $specification specification
     * @param QueryBuilder              $queryBuilder  query builder
     * @param mixed                     $value         parameter value
     * @param null|int|string           $type          ParameterType::* or \Doctrine\DBAL\Types\Type::* constant
     *
     * @return string placeholder name used
     */
    public static function createUniqueNamedParameter(SpecificationInterface $specification, QueryBuilder $queryBuilder, $value, $type = null): string
    {
        $parameters = $queryBuilder->getParameters();

        $placeHolder = sprintf(':dcValue_%s_%s', spl_object_id($specification), $parameters->count() + 1);

        $queryBuilder->setParameter(substr($placeHolder, 1), $value, $type);

        return $placeHolder;
    }
}
