<?php

$db = new mysqli("localhost", "root", "", "locations");

if ($db -> connect_errno) {
    echo "Failed to connect to MySQL: " . $db -> connect_error;
    exit();
}