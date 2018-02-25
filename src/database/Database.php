<?php
/**
 * Copyright (c) 2018 gerripeach <phillip.dev.p@gmail.com>
 * Distributed under the MIT License
 * (See accompanying file LICENSE or a copy at https://opensource.org/licenses/MIT)
 */

declare(strict_types=1);
namespace Slim\Database;

use PDO;

class Database {
    private $pdo_;
    private $dbName_;

    public function __construct(string $host, string $port, string $user, string $pass, string $base, string $dbName) {
        try {
            $this->pdo_ = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $base, $user, $pass);
            $this->pdo_->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbName_ = $dbName;
        }
        catch (PDOException $e) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo 'PDO Error: ' . $e->getMessage();
            die();
        }
    }

    public function getCode($code) {
        $query = "SELECT `used`, `for` FROM `$this->dbName_` WHERE `code`=:code";
        $statement = $this->pdo_->prepare($query);
        
        $statement->bindParam(':code', $code, PDO::PARAM_STR);
        $statement->execute();
        $row = $statement->fetch();

        if (!$row)
            return array(
                "not_found" => 1
            );

        if (preg_match('/(\d+)x(TRADINGCARDGOODS)/', $row['for'], $matches))
            return array(
                "type" => $matches[2],
                "amount" => intval($matches[1]),
                "used" => intval($row['used'])
            );

        return array(
            "type" => $row['for'],
            "used" => intval($row['used'])
        );
    }

    public function invalidateCode($code) {
        $query = "UPDATE `$this->dbName_` SET `used`=1 WHERE `code`=:code";

        $statement = $this->pdo_->prepare($query);
        $statement->bindParam(':code', $code, PDO::PARAM_STR);
        $statement->execute();

        return array(
            "successful" => $statement->rowCount()
        );
    }
}
