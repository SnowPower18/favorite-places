<?php

session_start();

include_once("../utils.php");
include_once("../database/db.php");


if(isset($_SESSION["authenticated"])) {
    // public and private locations 
    
    // private locations
    $result = $db->query("SELECT location_id, username, name, lat, lng, public FROM locations NATURAL JOIN users WHERE user_id = ".$_SESSION["user_id"]);
    if(!$result) {
        json_response(500, ["error" => "Server error, try later"]);
    }
    $privateLocations = $result->fetch_all(MYSQLI_ASSOC);
    
    // public but not from the user
    $result = $db->query("SELECT location_id, username, name, lat, lng, public FROM locations NATURAL JOIN users WHERE public = 1 AND user_id != ".$_SESSION["user_id"]);
    if(!$result) {
        json_response(500, ["error" => "Server error, try later"]);
    }
    $publicLocations = $result->fetch_all(MYSQLI_ASSOC);
    
    json_response(200, ["success" => "true", "publicLocations" => $publicLocations, "privateLocations" => $privateLocations]);

} else {
    // public locations only
    
    $result = $db->query("SELECT location_id, username, name, lat, lng FROM locations NATURAL JOIN users WHERE public = 1");
    if(!$result) {
        json_response(500, ["error" => "Server error, try later"]);
    }
    
    json_response(200, ["success" => "true", "locations" => $result->fetch_all(MYSQLI_ASSOC)]);
}