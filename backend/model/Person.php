<?php

use Person as GlobalPerson;

    include('../core/db/connect.php');
    class Person {
        public $id;
        public $name;
        public $farther_id;
        public $birth_year;
        public $birth_month;
        public $birth_day;
        public $other_name;
        public $conn;
        public $gender;

        public function __construct() {
            $this->conn = ConnectDB::getConnection();
        }

        public function __destruct() {
            $this->conn->close();
        }

        public function getListPersonWithParentId($parentId) {
            $sql = "SELECT * FROM persons where father_id = " . $parentId;
            $rs = $this->conn->query($sql);
            $children = $rs->fetch_all(MYSQLI_ASSOC);
            return $children;
        }

        public function update($node)
        {
            $mid = $node['mid'] != '' ? $node['mid'] : null ;
            $fid = $node['fid'] != '' ? $node['fid'] : null ;
            $sql = "UPDATE persons SET name = '". $node['name']. "' ";
            if ($mid) {
                $sql .= ", fid = ".$fid." " ;
            }
            if ($fid) {
                $sql .= ", mid = ".$mid." ";
            }
            $sql .= "where id = " . $node['id'];
            $rs = $this->conn->query($sql);
            return $rs;
        }

        public function addChild($name, $parent_id, $gender, $is_father)
        {
            if (($is_father) == 'true') {
                $sql = "INSERT INTO persons (name, fid, gender) VALUES ('". $name. "', ". $parent_id. ", '". $gender. "')";
            } else {
                $sql = "INSERT INTO persons (name, mid, gender) VALUES ('". $name. "', ". $parent_id. ", '". $gender. "')";
            }
            return $this->conn->query($sql);
        }

        public function addCouple($name, $couple_id, $gender, $is_husband)
        {
            $sql = "INSERT INTO persons (name, pids, gender) VALUES ('". $name. "', '".$couple_id."', '".$gender. "')";
            $this->conn->query($sql);
            $data = $this->getLast();
            $id = $data[0]['id'];
            $coupleId = json_decode($couple_id)[0];
            $sql2 = "UPDATE persons SET pids = '[$id]' WHERE id = $coupleId ";
            return $this->conn->query($sql2);
        }

        public function getLast()
        {
            $sql = "SELECT * FROM persons ORDER BY id DESC LIMIT 1";
            $rs = $this->conn->query($sql);
            $children = $rs->fetch_all(MYSQLI_ASSOC);
            return $children;
        }

        public function getAll()
        {
            $sql = "SELECT * FROM persons";
            $rs = $this->conn->query($sql);
            $datas = $rs->fetch_all(MYSQLI_ASSOC);
            return $datas;
        }
    }
?>