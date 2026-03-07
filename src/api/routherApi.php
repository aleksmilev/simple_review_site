<?php

class RoutherApi extends Routher
{
    private $apiController = null;
    private $apiMethod = null;
    private $apiParams = [];

    public function __construct()
    {
        $this->loadApiHelpers();

        $endpoint = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $path = parse_url($endpoint, PHP_URL_PATH) ?? '/';
        $normalizedEndpoint = $this->handleApiEndpoint($endpoint);
        
        $this->apiController = $normalizedEndpoint['controller'];
        $this->apiMethod = $normalizedEndpoint['method'];
        $this->apiParams = $normalizedEndpoint['params'];

        $this->methodType = $method;
    }

    private function loadApiHelpers()
    {
        $helperList = [
            'validationApi' => __DIR__ . '/validationApi.php',
            'controllerApi' => __DIR__ . '/controllerApi.php',
            'responceApi' => __DIR__ . '/responceApi.php',
        ];

        foreach ($helperList as $helper) {
            include_once($helper);
        }
    }

    private function handleApiEndpoint($endpoint)
    {
        $path = parse_url($endpoint, PHP_URL_PATH);
        $path = trim($path, "/");

        $segments = $path == "" ? [] : explode("/", $path);

        if (!isset($segments[0]) || $segments[0] != "api") {
            return [
                'controller' => null,
                'method' => null,
                'params' => [],
            ];
        }

        $controller = isset($segments[1]) && $segments[1] != "" ? strtolower($segments[1]) : null;
        $method = isset($segments[2]) && $segments[2] != "" ? $segments[2] : null;
        $params = array_slice($segments, 3);

        return [
            'controller' => $controller,
            'method' => $method,
            'params' => $params,
        ];
    }

    private function normalizeApiControllerFilename($controller)
    {
        $fileName = strtolower($controller);
        return __DIR__ . "/controller/" . $fileName . ".php";
    }

    private function normalizeApiController($controller)
    {
        return ucfirst(strtolower($controller)) . "Api";
    }

    protected function handle404()
    {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => '404 - API endpoint not found']);
        exit;
    }

    public function exec()
    {
        if ($this->apiController == null) {
            ResponceApi::handle404();
        }

        $fileName = $this->normalizeApiControllerFilename($this->apiController);
        if (!file_exists($fileName)) {
            ResponceApi::handle404();
        }
        require_once($fileName);

        $controllerName = $this->normalizeApiController($this->apiController);
        if (!class_exists($controllerName)) {
            ResponceApi::handle404();
        }

        $classReference = new $controllerName();
        
        if ($this->apiMethod == null) {
            ResponceApi::handle404();
        }
        
        $method = $this->apiMethod;

        if (!method_exists($classReference, $method)) {
            ResponceApi::handle404();
        }

        if (property_exists($classReference, 'requestRules')) {
            $requestRules = $classReference->requestRules;
            if (isset($requestRules[$method])) {
                $allowedMethods = $requestRules[$method];
                if (!in_array($this->methodType, $allowedMethods)) {
                    ResponceApi::handle405();
                }
            }
        }

        if (property_exists($classReference, 'adminMethods')) {
            $adminMethods = $classReference->adminMethods;
            if (in_array($method, $adminMethods)) {
                $validationResult = ValidationApi::validateAdminUser();
                if ($validationResult != true) {
                    ResponceApi::handle401();
                }
            }
        }

        $reflection = new \ReflectionMethod($classReference, $method);
        $requiredParams = $reflection->getNumberOfRequiredParameters();
        $totalParams = $reflection->getNumberOfParameters();
        $given = count($this->apiParams);

        if ($given < $requiredParams || $given > $totalParams) {
            ResponceApi::handle404();
        }

        return call_user_func_array([$classReference, $method], $this->apiParams);
    }
}