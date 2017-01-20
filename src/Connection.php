<?php

namespace obo\DataStorage;

class Connection extends \Dibi\Connection {

    /**
     * @var array
     */
    private $databases;

    /**
     * @var string
     */
    private $defaultDatabase;

    /**
     * @var string
     */
    const DATABASE_KEY = "database";

    /**
     * @var string
     */
    const DEFAULT_DATABASE_KEY = "defaultDatabase";

    /**
     * @var string
     */
    const DATABASES_KEY = "databases";

    /**
     * Additional connection options:
     *   - defaultDatabase (string)
     *   - databases (associative array of string) e.g. databaseAlias => databaseName
     *
     * @param mixed $config connection parameters
     * @param string $name connection name
     * @throws \Exception
     */
    public function __construct($config, $name = null) {
        if (is_string($config)) {
            parse_str($config, $config);
        } elseif ($config instanceof Traversable) {
            $tmp = [];
            foreach ($config as $key => $val) {
                $tmp[$key] = $val instanceof Traversable ? iterator_to_array($val) : $val;
            }
            $config = $tmp;
        } elseif (!is_array($config)) {
            throw new \InvalidArgumentException('Configuration must be array, string or object.');
        }

        if (!isset($config[static::DATABASES_KEY])) {
            throw new \InvalidArgumentException('Configuration key databases has to be defined.');
        }

        if (!is_array($config[static::DATABASES_KEY])) {
            throw new \InvalidArgumentException('Database configuration has to an array.');
        }

        if (!isset($config[static::DEFAULT_DATABASE_KEY])) {
            throw new \InvalidArgumentException('Default database is not defined.');
        }

        if (!isset($config[static::DATABASES_KEY][$config[static::DEFAULT_DATABASE_KEY]])) {
            throw new \InvalidArgumentException(\sprintf('Configuration for default database with name %s does not exists.', $config[static::DEFAULT_DATABASE_KEY]));
        }

        $this->databases = $config[static::DATABASES_KEY];
        $this->defaultDatabase = $this->getStorageNameByAlias($config[static::DEFAULT_DATABASE_KEY]);

        unset($config[static::DATABASES_KEY]);
        unset($config[static::DEFAULT_DATABASE_KEY]);
        $config[static::DATABASE_KEY] = $this->defaultDatabase;
        parent::__construct($config, $name);
    }

    /**
     * @param string $alias
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getStorageNameByAlias($alias) {
        if (!isset($this->databases[$alias])) {
            throw new \InvalidArgumentException(\sprintf('Database with alias %s does not exist in the configuration.', $alias));
        }
        return $this->databases[$alias];
    }

    /**
     * @return string
     */
    public function getDefaultStorageName() {
        return $this->defaultDatabase;
    }

    /**
     * @param string $databaseAlias
     * @throws \InvalidArgumentException
     */
    public function switchDatabase($databaseAlias) {
        $this->query("USE `" . $this->getStorageNameByAlias($databaseAlias) . "`");
    }

}
