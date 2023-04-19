<?php

session_start();

include_once("../utils.php");
include_once("../database/db.php");

if(!isset($_SESSION["authenticated"])) {
    json_response(401, ["error" => "Invalid credentials"]);
}

if(!isset($_SESSION["authenticated"]) || !isset($_POST["name"]) ||!isset($_POST["lat"])  ||!isset($_POST["lng"])) {
    json_response(400, ["error" => "Bad request"]);
}

$isPublic = isset($_POST["public"]) ? 1 : 0;
// insert the locations as private
$stmt = $db->prepare("INSERT INTO locations (user_id, name, lat, lng, public) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("isddi", $_SESSION["user_id"], $_POST["name"], $_POST["lat"], $_POST["lng"], $isPublic);

if(!$stmt->execute()) {
    json_response(500, ["error" => "Server error, try later"]);
}

// get id of the created record
$insertId = $db->insert_id;

$result = $db->query("SELECT * FROM locations WHERE location_id = ".$insertId);

// if failed query
if(!$result) {
    json_response(500, ["error" => "Server error, try later"]);
}

json_response(200, ["success" => "true", "location" => $result->fetch_assoc()]);