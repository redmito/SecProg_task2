<?php
// ur mission is after create connection to database, you need to implement the following functionalities in this file:
// 1. Create a POST request to handle registration form submission
// 2. Validate the input fields: username, email, password, confirm_password make sure confirm_password matches password
// 3. Check if the username or email already exists in the database
// 4. If validation passes, hash the password and insert the new user into the database
// 5. If registration is successful, redirect to login.php with a success message
include_once 'conn_db.php';

$username = filter_input(INPUT_POST, "username");
$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
$passw = filter_input(INPUT_POST, "password");
$c_passw = filter_input(INPUT_POST, "confirm_password");


session_start();

unset($_SESSION["error"]);

if (empty($username)){
    $_SESSION["error"] = "Username must be filled";
    header("Location: ./register.php");
    exit;
}
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $_SESSION["error"] = "Enter a valid email";
    header("Location: ./register.php");
    exit;
}

$usercheck =  mysqli_execute_query($link, "SELECT * FROM users WHERE username = ?", [$username]);
$emailcheck =  mysqli_execute_query($link, "SELECT * FROM users WHERE email = ?",  [$email]);

if (mysqli_num_rows($usercheck) > 0){
    $_SESSION["error"] = "Username is taken";
    header("Location: ./register.php");
    exit;
}
elseif (mysqli_num_rows($emailcheck) > 0){
    $_SESSION["error"] = "Email is taken";
    header("Location: ./register.php");
    exit;
}

if (strlen($passw) < 8){
    $_SESSION["error"] = "Password must be at least 8 characters long";
    header("Location: ./register.php");
    exit;
}
elseif (strcmp($passw, $c_passw) !== 0){
    $_SESSION["error"] = "Confirm password does not mach password";
    header("Location: ./register.php");
    exit;
}

$hash_pass = password_hash($passw, PASSWORD_DEFAULT);
mysqli_execute_query($link, "INSERT INTO users (username, email, password) VALUES (?, ?, ?) ", [$username, $email, $hash_pass]);

header("Location: ./login.php");
?>