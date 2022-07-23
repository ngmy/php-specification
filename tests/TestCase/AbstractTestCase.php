<?php

declare(strict_types=1);

namespace Ngmy\Specification\Test\TestCase;

use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\ORM\ORMSetup as DoctrineSetup;
use Illuminate\Database\Capsule\Manager as EloquentManager;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpEloquentManager();
    }

    protected function setUpEloquentManager(): void
    {
        $manager = new EloquentManager();
        $manager->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
        $manager->setAsGlobal();
        $manager->bootEloquent();
    }

    protected function createDoctrineEntityManager(): DoctrineEntityManager
    {
        $dbParams = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];
        $config = DoctrineSetup::createAttributeMetadataConfiguration([]);

        return DoctrineEntityManager::create($dbParams, $config);
    }
}
