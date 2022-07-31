<?php

declare(strict_types=1);

namespace Ngmy\Specification\Test\TestCase;

use Ngmy\Specification\Test\Stub\Orm\Doctrine\User as DoctrineUser;
use Ngmy\Specification\Test\Stub\Orm\Eloquent\User as EloquentUser;
use Ngmy\Specification\Test\Stub\Specification\ActiveUserSpecification;
use Ngmy\Specification\Test\Stub\Specification\PopularUserSpecification;

/**
 * @internal
 * @coversDefaultClass \Ngmy\Specification\OrSpecification
 */
class OrSpecificationTest extends AbstractTestCase
{
    /**
     * @covers ::applyToEloquent
     */
    public function testApplyToEloquent(): void
    {
        $query = EloquentUser::query();
        $spec1 = new PopularUserSpecification();
        $spec2 = new ActiveUserSpecification();
        $spec3 = $spec1->or($spec2);
        $spec3->applyToEloquent($query);

        $this->assertSame('select * from "users" where ("votes" > ?) or ("active" = ?)', $query->toSql());
        $this->assertSame([100, 1], $query->getBindings());
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
        $spec2 = new ActiveUserSpecification();
        $spec3 = $spec1->or($spec2);
        $spec3->applyToDoctrine($queryBuilder);

        $this->assertMatchesRegularExpression('/\ASELECT u FROM Ngmy\\\\Specification\\\\Test\\\\Stub\\\\Orm\\\\Doctrine\\\\User u WHERE u\.votes > :dcValue_[0-9]+_[0-9]+ OR u\.active = :dcValue_[0-9]+_[0-9]+\z/', $queryBuilder->getDQL());
        $this->assertSame([100, 1], $this->getDoctrineParametersArray($queryBuilder));
    }
}
