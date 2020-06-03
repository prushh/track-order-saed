<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "../utils.php";

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    bad_request();
}

require_once "../../database/connection.php";
require_once '../obj/status.php';

$db = new Database();
$conn = $db->openConnection();

$status = new Status($conn);

$stmt = NULL;

if(isset($_GET['id'])){
    if (!empty($_GET['id'])) {
        $stmt = $status->get_by_id($_GET['id']);
    } else {
        bad_request();
    }
} else {
    $stmt = $status->get_all($conn);
}

if ($stmt->rowCount() > 0) {

    $status_arr = array();
    $status_arr["results"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $status_item = array(
            "id" => $id,
            "title" => $title,
            "description" => $description
        );

        array_push($status_arr["results"], $status_item);
    }

    http_response_code(200);

    print json_encode($status_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Nessun status trovato"));
}
