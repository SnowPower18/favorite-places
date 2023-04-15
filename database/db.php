<?php

include_once("../utils.php");
try {
    $db = new mysqli("localhost", "root", "", "locations");
} catch(Exception $e) {
    json_response(500, ["error" => "Server error, try later"]);
}