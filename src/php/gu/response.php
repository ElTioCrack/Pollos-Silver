<?php
class ErrorResponse
{
    public $errorCode;
    public $errorMessage;

    public function __construct($errorCode, $errorMessage)
    {
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }
}

function sendResponse($response)
{
    $statusCode = isset($response->errorCode) ? $response->errorCode : 200;
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($response);
    die();
}
