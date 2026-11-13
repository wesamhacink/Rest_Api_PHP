
<?php

// 1. Set response type to JSON
header("Content-Type: application/json; charset=UTF-8");

// 2. Get HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

// 3. Get request headers
$headers = getallheaders();

// 4. Get header names without exposing sensitive values
$headerNames = array_keys($headers);

// 5. Read request body
$input = file_get_contents("php://input");

// 6. Convert JSON into PHP array
$data = json_decode($input, true);

// 7. Function to send JSON response
function sendResponse($code, $response)
{
    http_response_code($code);

    echo json_encode(
        $response,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

    exit;
}

// 8. Check request method
switch ($method) {

    // GET REQUEST
    case 'GET':

        sendResponse(200, [
            "status" => "success",
            "message" => "GET request received successfully",
            "method" => $method,
            "headers" => $headerNames
        ]);

        break;


    // POST REQUEST
    case 'POST':

        if (trim($input) === '') {

            sendResponse(400, [
                "status" => "error",
                "message" => "Request body is empty"
            ]);

        }

        if (json_last_error() !== JSON_ERROR_NONE) {

            sendResponse(400, [
                "status" => "error",
                "message" => "Invalid JSON format",
                "details" => json_last_error_msg()
            ]);

        }

        if (!is_array($data)) {

            sendResponse(400, [
                "status" => "error",
                "message" => "JSON must be an object or array"
            ]);

        }

        sendResponse(201, [
            "status" => "success",
            "message" => "POST request received successfully",
            "received_data" => $data
        ]);

        break;


    // PUT REQUEST
    case 'PUT':

        if (trim($input) === '') {

            sendResponse(400, [
                "status" => "error",
                "message" => "Request body is empty"
            ]);

        }

        if (json_last_error() !== JSON_ERROR_NONE) {

            sendResponse(400, [
                "status" => "error",
                "message" => "Invalid JSON format",
                "details" => json_last_error_msg()
            ]);

        }

        if (!is_array($data)) {

            sendResponse(400, [
                "status" => "error",
                "message" => "JSON must be an object or array"
            ]);

        }

        sendResponse(200, [
            "status" => "success",
            "message" => "PUT request received successfully",
            "updated_data" => $data
        ]);

        break;


    // DELETE REQUEST
    case 'DELETE':

        sendResponse(200, [
            "status" => "success",
            "message" => "DELETE request received successfully"
        ]);

        break;


    // UNSUPPORTED REQUEST
    default:

        sendResponse(405, [
            "status" => "error",
            "message" => "Method Not Allowed",
            "allowed_methods" => [
                "GET",
                "POST",
                "PUT",
                "DELETE"
            ]
        ]);

        break;

}

?>
