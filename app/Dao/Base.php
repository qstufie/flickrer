<?php
/**
 * base Dao
 * connects to PDO
 * and runs CRUD magic
 */
namespace Flickrer\Dao;

use Flickrer\App;

class Base
{
    /**
     * the connection
     * @var \PDO
     */
    protected $pdo;

    /**
     * pdo statement object
     * @var \PDOStatement
     */
    protected $stmt;


    /**
     * lazy loaded, and singleton pdo
     * to save connections
     * @return \PDO
     */
    public function db()
    {
        if (!$this->pdo instanceof \PDO) {
            $conf = App::getSetting('db');
            $this->pdo = new \PDO("mysql:host={$conf['host']};dbname={$conf['dbname']};charset=utf8", $conf['user'], $conf['pass']);

        }

        return $this->pdo;
    }

    /**
     * get the last insert id...
     * @return int
     */
    public function getLastInsertId()
    {
        return (int)$this->db()->lastInsertId();

    }


    /**
     * query
     * @param $sql
     * @param array $bind
     * @return \PDOStatement
     * @throws \Exception
     */
    public function query($sql, $bind = array())
    {
        $this->stmt = $this->db()->prepare($sql);
        if (is_object($this->stmt) && $this->stmt->execute($bind)) {
            // now, do i need to clear buffer?
            if (strpos(strtolower($sql), 'select') === false &&
                strpos(strtolower($sql), 'show') === false &&
                strpos(strtolower($sql), 'describe') === false &&
                strpos(strtolower($sql), 'pragma') === false
            ) {
                // should clear it!
                $this->stmt->fetchAll();
            }
            // return the statement
            return $this->stmt;
        }

        // otherwise, check error - and throw the exceptions out
        $err = 'unknown PDO error';
        $code = 100;
        if (is_object($this->db())) {
            $err = (array)$this->db()->errorInfo();
            $code = !empty($err[1]) ? (int)$err[1] : null;
            $err = !empty($err[2]) ? $err[2] : null;
        }
        if (is_object($this->stmt)) {
            $err = $this->stmt->errorInfo();
            $err = !empty($err[2]) ? $err[2] : null;
            $code = $this->stmt->errorCode();
        }
        throw new \Exception('PDO Error: ' + $err, $code);
    }

    /**
     * fetch all
     * @param $sql
     * @param array $bind
     * @return array
     * @throws \Exception
     */
    public function fetchAll($sql, $bind = array())
    {
        if ($this->query($sql, $bind)) {
            // start fetching
            $this->stmt->setFetchMode(\PDO::FETCH_ASSOC);
            return $this->stmt->fetchAll();
        }

    }

    /**
     * fetch one line
     * @param $sql
     * @param array $bind
     * @return mixed
     * @throws \Exception
     */
    public function fetchOne($sql, $bind = array())
    {
        $r = (array) $this->fetchAll($sql, $bind);
        if (count($r) > 0) return current($r);
    }

    /**
     * quote some string
     * @param $str
     * @return mixed
     */
    public function quote($str)
    {
        return $this->db()->quote($str);

    }// end quote


    /**
     * insert into table
     * @param array $data the raw data
     * @param string $tableName the table name
     * @return bool
     */
    public function insert($data, $tableName)
    {
        // break down...
        $breakDown = $this->breakDownData($data, $tableName, 'insert');
        // run
        $result = $this->query($breakDown['sql'], $breakDown['bind']);
        // insert id...
        $id = (int)$this->getLastInsertId();
        if ($id > 0) {
            return $id;
        }
        return $result;

    }

    /**
     * replace into the db...
     * @param array $data the source data
     * @param string $tableName the actual table name
     * @return bool
     */
    public function replaceInto($data, $tableName)
    {
        // break down...
        $breakDown = $this->breakDownData($data, $tableName, 'replace_into');
        // run
        return $this->query($breakDown['sql'], $breakDown['bind']);

    }

    /**
     * update table data
     * @param $data
     * @param $tableName
     * @param $condition
     * @return \PDOStatement
     * @throws \Exception
     */
    public function update($data, $tableName, $condition)
    {
        // condition must be there!!!
        if (empty($condition)) {
            throw new \Exception('UPDATE ' . $tableName . ' Condition is empty');
        }
        // break down...
        $breakDown = $this->breakDownData($data, $tableName, 'update');
        // run
        return $this->query($breakDown['sql'] . " WHERE {$condition}", $breakDown['bind']);

    }

    /**
     * delete
     * @param $tableName
     * @param $condition
     * @param array $bind
     * @return \PDOStatement
     * @throws \Exception
     */
    public function delete($tableName, $condition, $bind = array())
    {
        // condition must be there!!!
        if (empty($condition)) {
            throw new \Exception('DELETE FROM ' . $tableName . 'Condition is empty');
        }
        // otherwise, delete!
        return $this->query('DELETE FROM ' . $tableName . ' WHERE ' . $condition . ';', $bind);

    }

    /**
     * break down the data
     * @param $data
     * @param $tableName
     * @param $type
     * @return array
     * @throws \Exception
     */
    private function breakDownData($data, $tableName, $type)
    {
        // break down...
        if (!is_array($data) || empty($data) || empty($tableName)) {
            throw new \Exception("$type into $tableName Data is invalid");
        }
        // otherwise, do it...
        $fields = array();
        $bind = array();
        $holder = array();

        foreach ($data as $field => $val) {
            if ($type == 'insert') {
                $fields[] = $this->nameQuote($field);
            } else {
                $fields[] = $this->nameQuote($field) . ' = ?';
            }
            $bind[] = $val;
            $holder[] = '?';
        }
        $tableName = $this->nameQuote($tableName);
        // implement...
        $sql = null;
        switch ($type) {
            case 'insert':
                $sql = 'INSERT INTO ' . $tableName . '(' . implode(', ', $fields) .
                    ') VALUES (' . implode(', ', $holder) . ')';
                break;
            case 'update':
                $sql = 'UPDATE ' . $tableName . ' SET ' . implode(', ', $fields);
                break;
            case 'replace_into':
                $sql = 'REPLACE INTO ' . $tableName . ' SET ' . implode(', ', $fields);
                break;
        }
        return array('sql' => $sql, 'bind' => $bind);

    }

    /**
     * quote name
     * @param $name
     * @return mixed|string
     */
    public function nameQuote($name)
    {
        if (strpos($name, '.') !== false) {
            $name = str_replace('.', '`,`', $name);
        }
        return "`$name`";

    }

}
