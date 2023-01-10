<?php 
    include('../backend/model/Person.php');
    if (isset($_POST['type'])) {

        switch ($_POST['type']) {
            case 'update':
                update($_POST['node']);
                break;
            case 'addChild':
                addChild($_POST['name'], $_POST['parent_id'], $_POST['gender'], $_POST['is_father']);
                break;
            case 'addMother':
                addParent($_POST['id'], false);
                break;
            case 'addFather':
                addParent($_POST['id'], true);
                break;
            case 'addHusband':
                addCouple($_POST['name'], $_POST['couple_id'], $_POST['gender'], true);
                break;
            case 'addWife':
                addCouple($_POST['name'], $_POST['couple_id'], $_POST['gender'], false);
                break;
            case 'delete':
                delete($_POST['id']);
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

    function update($node)
    {
        $person = new Person();
        $data = $person->update($node);
        if ($data) {
            echo json_encode(array('status' =>true, 'message' => 'success', 'data' => $data));
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
            echo json_encode(array('status' =>true, 'message' => 'success', 'data' => $data));
        } else {
            echo json_encode(array('status' => false, 'message' => 'failure'));
        }
    }
    function addParent($id, $is_father = true)
    {
        try {
            $person = new Person();
            $parent = $person->addParent($id, $is_father);
            echo json_encode(array('status' =>true, 'message' => 'success', 'data' => $parent));
        } catch (\Throwable $th) {
            echo json_encode(array('status' => false, 'message' => 'failure'));
        }
    }
    function addCouple($name, $couple_id, $gender, $is_husband = true)
    {
        $person = new Person();
        $rs = $person->addCouple($name, $couple_id, $gender, $is_husband);
        if ($rs) {
            $data = $person->getLast();
            echo json_encode(array('status' =>true, 'message' => 'success', 'data' => $data));
        } else {
            echo json_encode(array('status' => false, 'message' => 'add couple failure'));
        }
    }

    function delete($id)
    {
        $person = new Person();
        $person->delete($id);
        echo json_encode(array('status' =>true, 'message' => 'success'));
    }
    
?>