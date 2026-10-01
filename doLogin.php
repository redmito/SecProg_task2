<?php
// ur mission is after create connection to database, you need to implement the following functionalities in this file:
// 1. Create a POST request to handle login form submission
// 2. Validate the username and password against the database
// 3. If the credentials are valid, start a session and redirect to index.php
// 4. If the credentials are invalid, redirect back to login.php with an error message
// 5. Dont forget to include session_start() at the beginning of the file to manage user sessions

include_once "./conn_db.php";
session_start();

unset($_SESSION["error"]);

$username = filter_input(INPUT_POST, "username");
$pass = filter_input(INPUT_POST, "password");

$db_check = mysqli_execute_query($link, "SELECT * FROM users WHERE username = ?", [$username]);
$row = mysqli_fetch_assoc($db_check);

if (strcmp($row['username'], $username) !== 0 || !password_verify($pass, $row['password'])){
    $_SESSION["error"] = "Incorrect username or password";
    header("Location: ./login.php");
    exit;
}

session_regenerate_id(true);
$_SESSION['username'] = $username;
header("Location: ./index.php");
exit;
?>