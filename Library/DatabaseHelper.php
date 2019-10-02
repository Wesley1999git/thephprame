<?php

namespace Library;

use Exception;

class DatabaseHelper{

    private $db;
    private $sql;

    function __construct() {
        $this->db = Database::init();
    }

    public function setSql($sql) {
        $this->sql = $sql;
    }

    public function getRow($data = null) {
        if (!$this->sql) {
            throw new Exception("No SQL query!");
        }

        $sth = $this->db->prepare($this->sql);
        $sth->execute($data);
        if ($row = $sth->fetch()) {
            return $row;
        } else {
            return null;
        }
    }

    public function getRows($data = null) {
        if (!$this->sql) {
            throw new Exception("No SQL query!");
        }
        $sth = $this->db->prepare($this->sql);
        $sth->execute($data);
        $result = array();
        while ($row = $sth->fetch()) {
            $result[] = $row;
        }
        return $result;
    }

    public function updateRecord($data = null) {
        $sth = $this->db->prepare($this->sql);
        if ($sth->execute($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function insertRecord($data = null) {
        $sth = $this->db->prepare($this->sql);
        if ($sth->execute($data)) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
}
