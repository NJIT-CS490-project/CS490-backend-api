<?php

class Database {

    public static function generate () {
        return new PDO(
            'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . MYSQL_DATABASE,
            MYSQL_USERNAME,
            MYSQL_PASSWORD,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
}
