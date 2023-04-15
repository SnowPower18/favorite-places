<?php

session_start();

include_once("../utils.php");
include_once("../database/db.php");

if(!(isset($_POST["username"]) && isset($_POST["password"]))) {
    json_response(401, ["error" => "Invalid credentials"]);
    die();
}

$passwordHash = password_hash($_POST["password"], PASSWORD_DEFAULT);

//controllo credenziali
$stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $_POST["username"], $passwordHash);

if(!$stmt->execute()) {
    json_response(500, ["error" => "Server error, retry later"]);
    die();
}

$result = $stmt->get_result();

if($result->num_rows == 0) {
    json_response(401, ["error" => "Invalid credentials"]);
    die();
}

$result = $result->fetch_assoc();

$_SESSION["authenticated"] = true;
$_SESSION["user_id"] = $result["user_id"];
$_SESSION["username"] = $result["username"];
$_SESSION["role"] = $result["role"];