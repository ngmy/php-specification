<?php

declare(strict_types=1);

namespace Ngmy\Specification;

use Doctrine\ORM\QueryBuilder;

/**
 * Doctrine trait.
 */
trait DoctrineTrait
{
    /**
     * Return a aliased column name.
     *
     * @param QueryBuilder $queryBuilder query builder
     * @param string       $columnName   column name
     *
     * @return string aliased column name
     */
    public function getAliasedColumnName(QueryBuilder $queryBuilder, string $columnName): string
    {
        $aliases = $queryBuilder->getRootAliases();

        return sprintf('%s.%s', $aliases[0], $columnName);
    }

    /**
     * Create a new unique named parameter from `$this`, and bind the `$value` to the `$queryBuilder`.
     *
     * @param QueryBuilder    $queryBuilder query builder
     * @param mixed           $value        parameter value
     * @param null|int|string $type         ParameterType::* or \Doctrine\DBAL\Types\Type::* constant
     *
     * @return string placeholder name used
     */
    public function createUniqueNamedParameter(QueryBuilder $queryBuilder, $value, $type = null): string
    {
        $parameters = $queryBuilder->getParameters();

        $placeHolder = sprintf(':dcValue_%s_%s', spl_object_id($this), $parameters->count() + 1);

        $queryBuilder->setParameter(substr($placeHolder, 1), $value, $type);

        return $placeHolder;
    }
}
