<?php

require('../config.php');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        post();
        break;
    case 'GET':
        get();
        break;
    case 'PUT':
        put();
        break;
    case 'DELETE':
        delete();
        break;
}

function get()
{
    require('../config.php');
    $result;

    if (isset($_GET['career'])) {
        $career = $_GET['career'];
        $result = $conn->query("SELECT * FROM Students WHERE career='$career'");
    } else {
        $result = $conn->query('SELECT * FROM Students');
    }

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    // $result['data'] = $data;
    echo json_encode($data);

    $conn->close();
    return;
}

function  post()
{
    require('../config.php');
    $_POST = json_decode(file_get_contents('php://input'), true);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $career = $_POST['career'];
    $status = $_POST['status'];

    if (isset($first_name) && isset($last_name) && isset($career) && isset($status)) {

        $insert = $conn->query(
            "INSERT INTO Students(first_name,last_name,career,status)
                VALUES('$first_name','$last_name','$career', $status);"
        );


        if ($insert) {
            $result = $conn->query('SELECT * FROM Students WHERE id=(SELECT LAST_INSERT_ID());');
            $data = $result->fetch_assoc();

            $response = array(
                'id' => $data['id'],
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'career' => $data['career'],
                'status'  => $data['status']
            );
            // echo $data['id'];
            // $result['data'] =  $response;
            echo json_encode($response);
        }
    } else {
        http_response_code(400);
    }

    $conn->close();
    return;
}

function put()
{
    require('../config.php');
    $_PUT = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'];

    $first_name = $_PUT['first_name'];
    $last_name = $_PUT['last_name'];
    $career = $_PUT['career'];
    $status = $_PUT['status'];

    $update;

    if (isset($first_name) && isset($last_name) && isset($career) && isset($status)) {


        $update = $conn->query("UPDATE Students SET first_name='$first_name', last_name='$last_name', career='$career', status=$status WHERE id='$id'");


        if ($update) {
            $result['data'] = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'career' => $career,
                'status' => $status
            );
            echo json_encode($result);
        }
    } else {
        http_response_code(400);
    }
    $conn->close();
    return;
}

function delete()
{
    require('../config.php');
    $id = $_GET['id'];
    $delete;

    if (isset($id)) {
        $delete = $conn->query("DELETE FROM Students WHERE id='$id'");
    } else {
        http_response_code(404);
    }

    if ($delete) {
        $result['data'] = 'success';
        echo json_encode($result);
    } else {
        http_response_code(400);
    }
    $conn->close();
    return;
}
