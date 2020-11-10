<?php
/**
 * db database library
 * Version 0.1.0
 * Copyright 2020, 4lkaid
 */

namespace sparrow;

use ErrorException;
use PDO;
use PDOException;

class db
{
    private $dsn;
    private $dbh        = null;
    private static $pdo = null;

    private function __construct($options)
    {
        try {
            if (!is_array($options)) {
                return false;
            }
            $host      = $options['host'] ?? '127.0.0.1';
            $port      = $options['port'] ?? '3306';
            $username  = $options['username'];
            $password  = $options['password'];
            $dbname    = $options['dbname'];
            $charset   = $options['charset'] ?? 'utf8mb4';
            $this->dsn = 'mysql:dbname=' . $dbname . ';host=' . $host . ';port=' . $port;
            $this->dbh = new PDO($this->dsn, $username, $password, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $charset]);
            $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    private function __clone()
    {
    }

    private function doPrepare($sql, $parameters)
    {
        $PDOStatement = $this->dbh->prepare($sql);
        $PDOStatement->execute($parameters);
        if ($PDOStatement->errorCode() !== '00000') {
            throw new ErrorException($PDOStatement->errorInfo()[2]);
        }
        return $PDOStatement;
    }

    public static function getInstance($options)
    {
        if (is_null(self::$pdo)) {
            self::$pdo = new self($options);
        }
        return self::$pdo;
    }

    public function query($sql, $parameters = [])
    {
        $PDOStatement = $this->doPrepare($sql, $parameters);
        return $PDOStatement->fetchAll();
    }

    public function execution($sql, $parameters = [])
    {
        $PDOStatement = $this->doPrepare($sql, $parameters);
        return $PDOStatement->rowCount();
    }

    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    public function commit()
    {
        return $this->dbh->commit();
    }

    public function rollBack()
    {
        return $this->dbh->rollBack();
    }

    public function getLastInsertId()
    {
        return $this->dbh->lastInsertId();
    }
}
