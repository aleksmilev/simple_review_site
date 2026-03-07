<?php

class RoutherApi extends Routher
{
    const API_CONTROLLERS = [
        'legal' => 'LegalApi',
    ];

    private $apiController = null;
    private $apiMethod = null;
    private $apiParams = [];

    public function __construct()
    {
        $endpoint = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $path = parse_url($endpoint, PHP_URL_PATH) ?? '/';
        $normalizedEndpoint = $this->handleApiEndpoint($endpoint);
        
        $this->apiController = $normalizedEndpoint['controller'];
        $this->apiMethod = $normalizedEndpoint['method'];
        $this->apiParams = $normalizedEndpoint['params'];

        $this->methodType = $method;

        include_once(__DIR__ . '/validationApi.php');
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
            $this->handle404();
        }

        $fileName = $this->normalizeApiControllerFilename($this->apiController);
        if (!file_exists($fileName)) {
            $this->handle404();
        }
        require_once($fileName);

        $controllerName = $this->normalizeApiController($this->apiController);
        if (!class_exists($controllerName)) {
            $this->handle404();
        }

        $classReference = new $controllerName();
        
        if ($this->apiMethod == null) {
            $this->handle404();
        }
        
        $method = $this->apiMethod;

        if (!method_exists($classReference, $method)) {
            $this->handle404();
        }

        $reflection = new \ReflectionMethod($classReference, $method);
        $requiredParams = $reflection->getNumberOfRequiredParameters();
        $totalParams = $reflection->getNumberOfParameters();
        $given = count($this->apiParams);

        if ($given < $requiredParams || $given > $totalParams) {
            $this->handle404();
        }

        return call_user_func_array([$classReference, $method], $this->apiParams);
    }
}