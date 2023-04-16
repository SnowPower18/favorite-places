<?php

session_start();

include_once("../utils.php");
include_once("../database/db.php");

if(!(isset($_POST["username"]) && isset($_POST["password"]))) {
    //400 Bad Request: client error (in this case missing params) 
    json_response(400, ["error" => "Bad request"]);
}

$passwordHash = password_hash($_POST["password"], PASSWORD_DEFAULT);

//check if username already exists
$stmt = $db->prepare("SELECT user_id FROM users WHERE username = ?");
$stmt->bind_param("s", $_POST["username"]);

if(!$stmt->execute()) {
    //500 Internal Server Error
    json_response(500, ["error" => "Server error, retry later1"]);
}

$result = $stmt->get_result();
if($result->num_rows != 0) {
    //409 Conflict
    json_response(409, ["error" => "Username already in use"]);
}

$stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $_POST["username"], $passwordHash);

if(!$stmt->execute()) {
    //500 Internal Server Error
    json_response(500, ["error" => "Server error, retry later2"]);
}

$_SESSION["authenticated"] = true;
$_SESSION["user_id"] = $db->insert_id;
$_SESSION["username"] = $_POST["username"];
$_SESSION["role"] = "user";

json_response(200, ["success" => "true"]);