<?php
    include_once "./conn_db.php";

    session_start();
    $result_user = mysqli_execute_query($link, "SELECT id FROM users where username = ?", [$_SESSION["username"]]);
    $user_id = mysqli_fetch_assoc($result_user);

    $file_id = $_GET["id"];
    $result = mysqli_execute_query($link, "SELECT stored_name FROM files WHERE id = ? and user_id = ?", [$file_id, $user_id["id"]]);
    $file_name = mysqli_fetch_assoc($result);
    $file_path = "uploads/" . $file_name["stored_name"];
    
    if (file_exists($file_path)){
        unlink($file_path);
        mysqli_execute_query($link, "DELETE FROM files WHERE id = ? AND user_id = ?", [$file_id, $user_id["id"]]);
    }

    header("Location: ./list.php");
    exit;
?>