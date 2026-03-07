<?php

class ResponceApi
{
    public static function returnData($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);

        exit;
    }

    public static function handle401()
    {
        $responseCode = 401;
        $response = [
            'error' => '401 - Authorization required',
        ];

        self::returnData($response, $responseCode);
    }

    public static function handle404()
    {
        $responseCode = 404;
        $response = [
            'error' => '404 - API endpoint not found',
        ];

        self::returnData($response, $responseCode);
    }

    public static function handle405()
    {
        $responseCode = 405;
        $response = [
            'error' => '405 - Method not allowed',
        ];

        self::returnData($response, $responseCode);
    }
}