[English](README.md) | [日本語](README-ja.md)

# PHP Specification

[![test](https://github.com/ngmy/php-specification/actions/workflows/test.yml/badge.svg)](https://github.com/ngmy/php-specification/actions/workflows/test.yml)
[![coverage](https://coveralls.io/repos/github/ngmy/php-specification/badge.svg?branch=master)](https://coveralls.io/github/ngmy/php-specification?branch=master)

This is a library to help implement the [specification pattern](https://www.martinfowler.com/apsupp/spec.pdf) in PHP.  
It provides on-memory validation, on-memory and ORM selection, and specification composite.

## Installation

```console
composer require ngmy/specification
```

## Usage

### Specification creation and on-memory validation and selection

Create your specification class by inheriting from the `AbstractSpecification` class.

Then implement the `isSatisfiedBy` method.  
In this method, write the criteria that satisfy the specification.

In addition, use the `@extends` annotation to write the object type expected by the `isSatisfiedBy` method
to facilitate static analysis.

```php
<?php

declare(strict_types=1);

use Ngmy\Specification\AbstractSpecification;

/**
 * Popular user specification.
 *
 * @extends AbstractSpecification<User>
 */
class PopularUserSpecification extends AbstractSpecification
{
    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return $candidate->getVotes() > 100;
    }
}
```

By calling the `isSatisfiedBy` method with the object to be verified,
you can verify that the object satisfies the specification.

```php
$spec = new PopularUserSpecification();
$spec->isSatisfiedBy($user);
```

Of course, it can also be used for selection.

```php
$spec = new PopularUserSpecification();
$popularUsers = array_filter(function (User $users) use ($spec): void {
    return $spec->isSatisfiedBy($user);
}, $users);
```

### ORM selection

#### Eloquent

Implement the `applyToEloquent` method.  
Write the selection criteria in this method using the `where` method, etc.

```php
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * {@inheritdoc}
 */
public function applyToEloquent(Builder $query): void
{
    $query->where('votes', '>', 100);
}
```

By calling the `applyToEloquent` method passing the Eloquent builder, you can add selection criteria to the query.

```php
$query = User::query(); // User is your Eloquent model
$spec = new PopularUserSpecification();
$spec->applyToEloquent($query);
$popularUsers = $query->get();
```

#### Doctrine

Implement the `applyToDoctrine` method.  
Write the selection criteria in this method using the `andWhere` method, etc.

```php
use Doctrine\ORM\QueryBuilder;
use Ngmy\Specification\Support\DoctrineUtils;

/**
 * {@inheritdoc}
 */
public function applyToDoctrine(QueryBuilder $queryBuilder): void
{
    $queryBuilder->andWhere($queryBuilder->expr()->gt(
        DoctrineUtils::getAliasedColumnName($queryBuilder, 'votes'),
        DoctrineUtils::createUniqueNamedParameter($this, $queryBuilder, 100),
    ));
}
```

By calling the `applyToDoctrine` method passing the query builder, you can add selection criteria to the query.

```php
/** @var \Doctrine\ORM\EntityManager $entityManager */
$queryBuilder = $entityManager->createQueryBuilder();
$queryBuilder->select('u')->from(User::class, 'u'); // User is your Doctrine entity
$spec = new PopularUserSpecification();
$spec->applyToDoctrine($queryBuilder);
$popularUsers = $queryBuilder->getQuery()->getResult();
```

### Composite

You can compose specifications with AND, OR, and NOT.  
When composing a specification, the criteria writed in the `isSatisfiedBy`, `applyToEloquent` and `applyToDoctrine`
methods are also composited.

#### AND

By passing an instance of another specification to the specification's `and` method and calling it,
you can generate a new specification that is an AND composite of the two specifications.

```php
$spec1 = new Specification1();
$spec2 = new Specification2();
$spec3 = $spec1->and($spec2);
```

#### OR

By passing an instance of another specification to the specification's `or` method and calling it,
you can generate a new specification that is an OR composite of the two specifications.

```php
$spec1 = new Specification1();
$spec2 = new Specification2();
$spec3 = $spec1->or($spec2);
```

#### NOT

By calling the `not` method of the specification, you can generate a new specification that is NOT composite of itself.

```php
$spec1 = new Specification1();
$spec2 = $spec1->not();
```

## Example of use

- [ngmy/php-specification-example](https://github.com/ngmy/php-specification-example)
  - This project is a code example of using the PHP Specification to implement a specification pattern.  
    It is written following Domain-Driven Design approach and has a code example of combining a specification and a repository.  
    It uses Eloquent and Doctrine for the ORM.

## License

PHP Specification is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
