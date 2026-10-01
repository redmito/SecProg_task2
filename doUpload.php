<?php
// ur mission is after create connection to database, you need to implement the following functionalities in this file:
// 1. Create a POST request to handle file upload form submission & validate the user session to ensure the user is authenticated before allowing file upload
// 2. Validate the uploaded file to ensure it meets the required criteria (e.g., file type, size limit)
// try to limit the file size to 5MB and only allow certain file types (e.g., PDF, DOCX, JPG, PNG)
// 3. Move the uploaded file to a designated directory on the server (e.g., "uploads/")
// 4. Store the file information (e.g., file name, size, upload date, user ID) in the database for future reference

session_start();

unset($_SESSION["error"]);

if (!isset($_SESSION["username"])){
    header("Location: ./login.php");
    exit;
}

$file = $_FILES["fileUpload"];

$file_ext = pathinfo($file["name"], PATHINFO_EXTENSION);
$ext_list = ["pdf", "docx", "jpg", "png"];

if(!in_array($file_ext, $ext_list)){
    $_SESSION["error"] = "File must be in PDF/DOCX/JPG/PNG format";
    header("Location: ./upload.php");
    exit;
}

if ($file["size"] > 5 * 1024**2){
    $_SESSION["error"] = "File msut be less than 5 MB";
    header("Location: ./upload.php");
    exit;
}

$src = $file["tmp_name"];

$file_id = uniqid("upload", true);

$stored_name = $file_id . ".". $file_ext;
$dest = "./uploads/" . $stored_name;

move_uploaded_file($src, $dest);

include_once "./conn_db.php";

$result = mysqli_execute_query($link, "SELECT id FROM users WHERE username = ?", [$_SESSION["username"]]);
$user = mysqli_fetch_assoc($result);
$user_id = $user["id"];

mysqli_execute_query($link, "INSERT INTO files (user_id, original_name, stored_name, file_size, file_type) VALUES (?, ?, ?, ?, ?)", 
[$user_id, $file["name"], $stored_name, $file["size"], $file["type"]]);
 
header("Location: ./list.php");
exit;
?>