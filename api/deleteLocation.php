<?php

session_start();

include_once("../utils.php");

if(!isset($_SESSION["authenticated"])) {
    json_response(401, ["error" => "Invalid credentials"]);
}
if(!isset($_GET["location_id"]) || !is_numeric($_GET["location_id"])) {
    json_response(400, ["error" => "Bad request"]);
}

include_once("../database/db.php");

$stmt = $db->prepare("SELECT * FROM locations WHERE location_id = ?");
$stmt->bind_param("i", $_GET["location_id"]);

if(!$stmt->execute()) {
    json_response(500, ["error" => "Server error, try later"]);
}

$result = $stmt->get_result()->fetch_assoc();

if($result["user_id"] != $_SESSION["user_id"] && $_SESSION["role"] != "admin") {
    // forbidden status code (missing authorization)
    json_response(403, ["error" => "Not high enough permissions"]);
}

$stmt = $db->prepare("DELETE FROM locations WHERE user_id = ? AND location_id = ?");
$stmt->bind_param("ii", $_SESSION["user_id"], $_GET["location_id"]);

if(!$stmt->execute()) {
    json_response(500, ["error" => "Server error, try later"]);
}

json_response(200, ["success" => "true"]);