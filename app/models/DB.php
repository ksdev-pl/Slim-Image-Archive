<?php

class DB
{
    /** @type PDO $dbh  Database handle */
    private $dbh;

    /** @type PDOStatement $stmt */
    private $stmt;

    /**
     * @param $host
     * @param $user
     * @param $pass
     * @param $dbname
     *
     * @throws PDOException  Remember to catch this exception. Error could reveal password!
     */
    public function __construct($host, $user, $pass, $dbname)
    {
        $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ];

        $this->dbh = new PDO($dsn, $user, $pass, $options);
    }

    /**
     * Prepare & execute query
     *
     * @param string $query  MySQL query with optional placeholders (see below)
     * @param array $params  Array of placeholders with corresponding values ([':placeholder' => $value])
     *
     * @throws PDOException  If dbh cannot prepare statement. Depends on ERRMODE_EXCEPTION
     */
    private function run($query, $params)
    {
        $this->stmt = $this->dbh->prepare($query);

        foreach ($params as $key => &$value) {
            $this->stmt->bindParam($key, $value);
        }

        $this->stmt->execute();
    }

    /**
     * Get all rows from query result
     *
     * @param string $query
     * @param array $params
     *
     * @return array
     */
    public function select($query, $params = [])
    {
        $this->run($query, $params);

        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get one row from query result
     *
     * @param string $query
     * @param array $params
     *
     * @return array
     */
    public function selectOne($query, $params = [])
    {
        $this->run($query, $params);

        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insert row into table
     *
     * @param string $query
     * @param array $params
     *
     * @return int  Number of affected rows
     */
    public function insert($query, $params = [])
    {
        $this->run($query, $params);

        return $this->stmt->rowCount();
    }

    /**
     * Delete row from table
     *
     * @param string $query
     * @param array $params
     *
     * @return int  Number of affected rows
     */
    public function delete($query, $params = [])
    {
        $this->run($query, $params);

        return $this->stmt->rowCount();
    }

    /**
     * Update row in table
     *
     * @param string $query
     * @param array $params
     *
     * @return int  Number of affected rows
     */
    public function update($query, $params = [])
    {
        $this->run($query, $params);

        return $this->stmt->rowCount();
    }
}