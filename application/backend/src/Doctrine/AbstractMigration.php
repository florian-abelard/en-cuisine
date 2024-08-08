<?php

namespace App\Doctrine;

use Doctrine\Migrations\AbstractMigration as BaseAbstractMigration;

abstract class AbstractMigration extends BaseAbstractMigration
{
    public function addMultipleSql(string $sql, array $paramsPool, array $types = []): void
    {
        foreach ($paramsPool as $params) {
            $this->addSql($sql, $params, $types);
        }
    }
}
