<?php

// Database Connection Configuration
$db_connection = mysqli_connect('localhost', 'root', '', 'task_manager');

// Function get all record of tasks
function tasks_list()
{
    global $db_connection;

    $tasks_list = mysqli_query($db_connection, 'select * from tasks');

    return $tasks_list;
}

// Function get list of tasks use paginate method
function tasks_list_paginate($page, $limit_record)
{
    global $db_connection;

    $tasks_list = mysqli_query($db_connection, "select * from tasks limit $page, $limit_record");

    return $tasks_list;
}

// Logical POST Method Insert Data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Create and Update Record Validation
    if (!isset($_POST['task_id'])) {
        // Create Record
        $title = $_POST['title'];
        $status = $_POST['status'];
        $description = $_POST['description'];

        // Query Store Data to Table
        $query = "INSERT INTO tasks (title, status, description) VALUES ('$title', '$status', '$description')";

        // Checking Query Success
        if (mysqli_query($db_connection, $query)) {

            // Redirect Back to Index
            header('Location: ../index.php');
        } else {
            echo 'Error: ' . $query . '<br>' . mysqli_error($conn);
        }
    } else {
        // Update Status Record
        $task_id = $_POST['task_id'];
        $status = $_POST['task_status'];

        // Update Data to Table
        $query = "UPDATE tasks SET status='$status' WHERE id='$task_id'";

        // Checking Query Success
        if (mysqli_query($db_connection, $query)) {
            // Redirect Back to Index
            header('Location: ../index.php');
        } else {
            echo 'Error: ' . $query . '<br>' . mysqli_error($conn);
        }
    }
}
?>
