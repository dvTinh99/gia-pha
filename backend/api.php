<?php 
    require_once('../backend/model/Person.php');
    if (isset($_POST['type'])) {

        switch ($_POST['type']) {
            case 'update':
                update($_POST['id'], $_POST['value']);
                break;
            case 'addChild':
                addChild($_POST['name'], $_POST['parent_id'], $_POST['gender'], $_POST['is_father']);
                break;
            case 'addMother':
                addChild($_POST['name'], $_POST['parent_id'], $_POST['gender'], $_POST['is_father']);
                break;
            case 'addFather':
                addChild($_POST['name'], $_POST['parent_id'], $_POST['gender'], $_POST['is_father']);
                break;
            case 'addHusband':
                addCouple($_POST['name'], $_POST['couple_id'], $_POST['gender'], true);
                break;
            case 'addWife':
                addCouple($_POST['name'], $_POST['couple_id'], $_POST['gender'], false);
                break;
        }
    }
    if (isset($_GET['type'])){
        switch ($_GET['type']) {
            case 'getLists':
                getLists();
                break;
        }
    }

    function getLists()
    {
        $person = new Person();
        $data = $person->getAll();
        echo json_encode($data);
    }

    function update($id, $name)
    {
        $person = new Person();
        $rs = $person->update($id, $name);
        if ($rs) {
            echo json_encode(array('status' =>true, 'message' => 'success'));
        } else {
            echo json_encode(array('status' => false, 'message' => 'failure'));
        }
    }

    function addChild($name, $parent_id, $gender, $is_father = true)
    {
        $person = new Person();
        $rs = $person->addChild($name, $parent_id, $gender, $is_father);

        if ($rs) {
            $data = $person->getLast();
            echo json_encode(array('status' =>true, 'message' => 'success', 'data' => $data[0]));
        } else {
            echo json_encode(array('status' => false, 'message' => 'failure'));
        }
    }
    function addParent($name, $node_id, $gender, $is_father = true)
    {
        $person = new Person();
        // $rs = $person->addChild($name, $parent_id, $gender, $is_father);

        // if ($rs) {
        //     $data = $person->getLast();
        //     echo json_encode(array('status' =>true, 'message' => 'success', 'data' => $data[0]));
        // } else {
        //     echo json_encode(array('status' => false, 'message' => 'failure'));
        // }
    }
    function addCouple($name, $couple_id, $gender, $is_husband = true)
    {
        $person = new Person();
        $rs = $person->addCouple($name, $couple_id, $gender, $is_husband);
        if ($rs) {
            $data = $person->getLast();
            echo json_encode(array('status' =>true, 'message' => 'success', 'data' => $data[0]));
        } else {
            echo json_encode(array('status' => false, 'message' => 'add couple failure'));
        }
    }
    
?>