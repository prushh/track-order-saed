<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../utils.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    bad_request();
}

include_once '../../connection.php';
include_once '../objects/tracking.php';

$db = new Database();
$conn = $db->openConnection();

