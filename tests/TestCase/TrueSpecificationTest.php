<?php

declare(strict_types=1);

namespace Ngmy\Specification\Test\TestCase;

use Ngmy\Specification\Test\Stub\Orm\Doctrine\User as DoctrineUser;
use Ngmy\Specification\Test\Stub\Orm\Eloquent\User as EloquentUser;
use Ngmy\Specification\TrueSpecification;

/**
 * @internal
 * @coversDefaultClass \Ngmy\Specification\TrueSpecification
 */
class TrueSpecificationTest extends AbstractTestCase
{
    /**
     * @covers ::applyToEloquent
     */
    public function testApplyToEloquent(): void
    {
        $query = EloquentUser::query();
        $spec1 = TrueSpecification::getInstance();
        $spec1->applyToEloquent($query);

        $this->assertSame('select * from "users" where 1 = 1', $query->toSql());
    }

    /**
     * @covers ::applyToDoctrine
     */
    public function testApplyToDoctrine(): void
    {
        $entityManager = $this->createDoctrineEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('u')->from(DoctrineUser::class, 'u');
        $spec1 = TrueSpecification::getInstance();
        $spec1->applyToDoctrine($queryBuilder);

        $this->assertSame('SELECT u FROM Ngmy\Specification\Test\Stub\Orm\Doctrine\User u WHERE 1 = 1', $queryBuilder->getDQL());
    }
}
