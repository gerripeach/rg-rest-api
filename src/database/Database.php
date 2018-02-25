<?php
/**
 * Copyright (c) 2018 gerripeach <phillip.dev.p@gmail.com>
 * Distributed under the MIT License
 * (See accompanying file LICENSE or a copy at https://opensource.org/licenses/MIT)
 */

declare(strict_types=1);
namespace Slim\Database;

use \Monolog\Logger;
use PDO;

class Database {
    private $pdo_;
    private $tableName_;
    private $logger_;

    public function __construct(Logger $logger, string $host, string $port, string $user, string $pass, string $base, string $tableName) {
        try {
            $this->pdo_ = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $base, $user, $pass);
            $this->pdo_->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->tableName_ = $tableName;
            $this->logger_ = $logger;
        }
        catch (PDOException $e) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            $this->logger_->err("PDO Connection: $e->getMessage()");
            die();
        }
    }

    public function getCode(string $code) {
        $query = "SELECT `used`, `for` FROM `$this->tableName_` WHERE `code`=:code";
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

    public function invalidateCode(string $code, int $redeemerGUID) {
        $query = "UPDATE `$this->tableName_` SET `used`=1, `redeemerGuid`=:guid, `redeemTime`=NOW() WHERE `code`=:code AND `used`=0";
        $statement = $this->pdo_->prepare($query);
        $statement->bindParam(':guid', $redeemerGUID, PDO::PARAM_INT);
        $statement->bindParam(':code', $code, PDO::PARAM_STR);
        $statement->execute();

        $successful = $statement->rowCount();
        if ($successful)
            $this->logger_->info("Successful invalidated code: $code redeemer guid: $redeemerGUID");

        return array(
            "successful" => $statement->rowCount()
        );
    }
}
