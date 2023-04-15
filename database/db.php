<?php

include_once("../utils.php")

$db = new mysqli("localhost", "root", "", "locations");

if ($db -> connect_errno) {
    json_response(500, ["error" => "Server error, try later"]);
    // DEBUG ONLY
    // echo "Failed to connect to MySQL: " . $db -> connect_error;
    // exit();
}