[English](README.md) | [日本語](README-ja.md)

# PHP Specification

[![test](https://github.com/ngmy/php-specification/actions/workflows/php.yml/badge.svg)](https://github.com/ngmy/php-specification/actions/workflows/php.yml)
[![coverage](https://coveralls.io/repos/github/ngmy/php-specification/badge.svg?branch=master)](https://coveralls.io/github/ngmy/php-specification?branch=master)

これはPHPで[仕様パターン](https://www.martinfowler.com/apsupp/spec.pdf)を実装するのを手助けするライブラリです。  
メモリー上での検証、メモリー上およびORMでの選択、および仕様の合成を提供します。

## インストール方法

```console
composer require ngmy/specification
```

## 使用方法

### 仕様の作成およびメモリー上での検証・選択

`AbstractSpecification`クラスを継承しあなたの仕様クラスを作成します。

そして`isSatisfiedBy`メソッドを実装します。  
このメソッドに仕様を満たす条件を記述します。

さらに`@extends`アノテーションで`isSatisfiedBy`メソッドが期待するオブジェクトの型を記述しておくと静的解析が捗ります。

```php
<?php

declare(strict_types=1);

use Ngmy\Specification\AbstractSpecification;

/**
 * 人気ユーザー仕様。
 *
 * @extends AbstractSpecification<User>
 */
class PopularUserSpecification extends AbstractSpecification
{
    /**
     * @inheritdoc
     */
    public function isSatisfiedBy($candidate): bool
    {
        return $candidate->getVotes() > 100;
    }
}
```

`isSatisfiedBy`メソッドに検証したいオブジェクトを渡して呼び出すことで、
そのオブジェクトが仕様を満たすかどうかを検証できます。

```php
$spec = new PopularUserSpecification();
$spec->isSatisfiedBy($user);
```

もちろん選択にも使えます。

```php
$spec = new PopularUserSpecification();
$popularUsers = array_filter(function (User $users) use ($spec): void {
    return $spec->isSatisfiedBy($user);
}, $users);
```

### ORMでの選択

#### Eloquent

`applyToEloquent`メソッドを実装します。  
このメソッドに`where`メソッド等で選択条件を記述します。

```php
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

/**
 * @inheritdoc
 */
public function applyToEloquent(EloquentBuilder $query): void
{
    $query->where('votes', '>', 100);
}
```

`applyToEloquent`メソッドにEloquentビルダーを渡して呼び出すことで、クエリーに選択条件を追加できます。

```php
$query = User::query(); // UserはあなたのEloquentモデルです
$spec = new PopularUserSpecification();
$spec->applyToEloquent($query);
$popularUsers = $query->get();
```

#### Doctrine

`applyToDoctrine`メソッドを実装します。  
このメソッドに`andWhere`メソッド等で選択条件を記述します。

```php
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

/**
 * @inheritdoc
 */
public function applyToDoctrine(DoctrineQueryBuilder $queryBuilder): void
{
    $queryBuilder->andWhere(sprintf('%s.votes > 100', $queryBuilder->getRootAliases()[0]));
}
```

`applyToDoctrine`メソッドにクエリービルダーを渡して呼び出すことで、クエリーに選択条件を追加できます。

```php
/** @var \Doctrine\ORM\EntityManager $entityManager */
$queryBuilder = $entityManager->createQueryBuilder();
$queryBuilder->select('u')->from(User::class, 'u'); // UserはあなたのDoctrineエンティティーです
$spec = new PopularUserSpecification();
$spec->applyToDoctrine($queryBuilder);
$popularUsers = $queryBuilder->getQuery()->getResult();
```

### 合成

仕様をAND、OR、NOTで合成できます。  
仕様を合成すると`isSatisfiedBy`メソッドや`applyToEloquent`、`applyToDoctrine`メソッドに記述した条件も合成されます。

#### AND

仕様の`and`メソッドに別の仕様のインスタンスを渡して呼び出すことで、2つの仕様をANDした新しい仕様を生成できます。

```php
$spec1 = new Specification1();
$spec2 = new Specification2();
$spec3 = $spec1->and($spec2);
```

#### OR

仕様の`or`メソッドに別の仕様のインスタンスを渡して呼び出すことで、2つの仕様をORした新しい仕様を生成できます。

```php
$spec1 = new Specification1();
$spec2 = new Specification2();
$spec3 = $spec1->or($spec2);
```

#### NOT

仕様の`not`メソッドを呼び出すことで、自分自身をNOTした新しい仕様を生成できます。

```php
$spec1 = new Specification1();
$spec2 = $spec1->not();
```

## License

PHP Specificationは[MITライセンス](http://opensource.org/licenses/MIT)の下で提供されるオープンソースソフトウェアです。
