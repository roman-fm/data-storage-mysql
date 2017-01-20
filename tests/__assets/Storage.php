<?php

namespace obo\DataStorage\Tests\Assets;

class Storage {

    /**
     * @var array
     */
    private static $config = [
        "hostname" => "mysql_current",
        "defaultDatabase" => "testDb",
        "username" => "root",
        "password" => "root",
        "databases" => [
            "testDb" => "obo-test",
            "testDb2" => "obo-test2"
        ]
    ];

    /**
     * @var \obo\Interfaces\IConnection
     */
    private static $connection = null;

    /**
     *
     * @var \obo\DataStorage\MySQL
     */
    private static $dataStorage = null;

    /**
     * @return \obo\DataStorage\Connection
     */
    public static function getConnection() {
        if (static::$connection === null) {
            static::$connection = new \obo\DataStorage\Connection(static::$config);
        }

        return static::$connection;
    }

    /**
     * @return \obo\DataStorage\MySQL
     */
    public static function getMySqlDataStorage() {
        if (static::$dataStorage === null) {
            static::$dataStorage = new \obo\DataStorage\MySQL(static::getConnection(), new \obo\DataStorage\DataConverters\DefaultMysqlDataConverter());
        }

        return static::$dataStorage;
    }

}
