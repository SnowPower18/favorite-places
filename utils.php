<?php

function json_response($status, $resArray) {
    http_response_code($status);
    $response = json_encode((object) $resArray);
    echo $response;
    exit();
}