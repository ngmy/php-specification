<?php

declare(strict_types=1);

namespace Ngmy\Specification\Test\TestCase;

use Ngmy\Specification\FalseSpecification;
use Ngmy\Specification\Test\Stub\Orm\Doctrine\User as DoctrineUser;
use Ngmy\Specification\Test\Stub\Orm\Eloquent\User as EloquentUser;

/**
 * @internal
 * @coversDefaultClass \Ngmy\Specification\FalseSpecification
 */
class FalseSpecificationTest extends AbstractTestCase
{
    /**
     * @covers ::applyToEloquent
     */
    public function testApplyToEloquent(): void
    {
        $query = EloquentUser::query();
        $spec1 = FalseSpecification::getInstance();
        $spec1->applyToEloquent($query);

        $this->assertSame('select * from "users" where 1 = 0', $query->toSql());
    }

    /**
     * @covers ::applyToDoctrine
     */
    public function testApplyToDoctrine(): void
    {
        $entityManager = $this->createDoctrineEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('u')->from(DoctrineUser::class, 'u');
        $spec1 = FalseSpecification::getInstance();
        $spec1->applyToDoctrine($queryBuilder);

        $this->assertSame('SELECT u FROM Ngmy\Specification\Test\Stub\Orm\Doctrine\User u WHERE 1 = 0', $queryBuilder->getDQL());
    }
}
