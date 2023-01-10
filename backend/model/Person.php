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

        public function addParent($id, $is_father)
        {
            $gender = $is_father ? 'male' : 'female';
            $sql = "INSERT INTO persons (name, gender, pids) VALUES ('A', '". $gender. "', '[]')";
            $rs = $this->conn->query($sql);
            if (($is_father) == 'true' && $rs) {
                $father = $this->getLast();
                $me = $this->find($id);
                $me['fid'] = $father['id'];
                $this->update($me);
                return $father;
            } else {
                $mother = $this->getLast();
                $me = $this->find($id);
                $me['mid'] = $mother['id'];
                $this->update($me);
                return $mother;
            }
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
            $pids = $node['pids'];
            $sql = "UPDATE persons SET name = '". $node['name']. "' ";
            if ($mid) {
                $sql .= ", mid = ".$mid." " ;
            } else {
                $sql .= ", mid = NULL" ;
            }
            if ($fid) {
                $sql .= ", fid = ".$fid." ";
            } else {
                $sql .= ", fid = NULL";
            }
            if ($pids) {
                $coupleId = $pids[0];
                $sql .= ", pids = '[".$coupleId."]' ";
            } else {
                $sql .= ", pids = '[]' ";
            }
            $sql .= "where id = " . $node['id'];
            $rs = $this->conn->query($sql);
            return $this->find($node['id']);
        }

        public function find($id) {
            $sql = "SELECT * FROM persons WHERE id = $id LIMIT 1";
            $rs = $this->conn->query($sql);
            $person = $rs->fetch_all(MYSQLI_ASSOC);
            return $person[0];
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
            $children = $rs->fetch_all(MYSQLI_ASSOC)[0];
            return $children;
        }

        public function getAll()
        {
            $sql = "SELECT * FROM persons";
            $rs = $this->conn->query($sql);
            $datas = $rs->fetch_all(MYSQLI_ASSOC);
            return $datas;
        }

        public function delete($id) {
            $sql = "DELETE FROM persons WHERE id = $id;";
            $rs = $this->conn->query($sql);
            return $rs;
        }
    }
?>