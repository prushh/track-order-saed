<?php
function bad_request()
{
    http_response_code(400);
    echo json_encode(array("message" => "Richiesta malformata o illegale."));
    exit(0);
}
