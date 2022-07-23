<?php

declare(strict_types=1);

namespace Ngmy\Specification\Test\TestCase;

use Ngmy\Specification\Test\Stub\Orm\Doctrine\User as DoctrineUser;
use Ngmy\Specification\Test\Stub\Orm\Eloquent\User as EloquentUser;
use Ngmy\Specification\Test\Stub\Specification\PopularUserSpecification;

/**
 * @internal
 * @coversDefaultClass \Ngmy\Specification\NotSpecification
 */
class NotSpecificationTest extends AbstractTestCase
{
    /**
     * @covers ::applyToEloquent
     */
    public function testApplyToEloquent(): void
    {
        $query = EloquentUser::query();
        $spec1 = new PopularUserSpecification();
        $spec2 = $spec1->not();
        $spec2->applyToEloquent($query);

        $this->assertSame('select * from "users" where not ("votes" > ?)', $query->toSql());
        $this->assertSame([100], $query->getBindings());
    }

    /**
     * @covers ::applyToDoctrine
     */
    public function testApplyToDoctrine(): void
    {
        $entityManager = $this->createDoctrineEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('u')->from(DoctrineUser::class, 'u');
        $spec1 = new PopularUserSpecification();
        $spec2 = $spec1->not();
        $spec2->applyToDoctrine($queryBuilder);

        $this->assertSame('SELECT u FROM Ngmy\Specification\Test\Stub\Orm\Doctrine\User u WHERE NOT(u.votes > 100)', $queryBuilder->getDQL());
    }
}
