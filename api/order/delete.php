<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../utils.php";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    bad_request();
}

require_once "../database/connection.php";
require_once '../obj/order.php';

$db = new Database();
$conn = $db->openConnection();

$order = new Order($conn);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $order->id = $data->id;

    if ($order->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "Ordine eliminato."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Ordine inesistente o protetto."));
    }
} else {
    bad_request();
}
