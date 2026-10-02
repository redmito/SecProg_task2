<?php 
    include_once "./conn_db.php";

    session_start();
    $result_user = mysqli_execute_query($link, "SELECT id FROM users where username = ?", [$_SESSION["username"]]);
    $user_id = mysqli_fetch_assoc($result_user);

    $file_id = $_GET["id"];
    $result_file = mysqli_execute_query($link, "SELECT original_name, stored_name, user_id FROM files WHERE id = ? AND user_id = ?", [$file_id, $user_id["id"]]);
    $file_name = mysqli_fetch_assoc($result_file);
    $file_path = "uploads/" . $file_name["stored_name"];

    if (file_exists($file_path)){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $file_name["original_name"]);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path)); 
        flush();    
        
        readfile($file_path);
        header("Location: ./list.php");
        exit; 
    }
?>