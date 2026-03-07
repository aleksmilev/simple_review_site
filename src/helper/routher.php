<?php

class Routher
{
    private $controller = null;
    private $method = null;
    private $params = [];

    protected $methodType = null;

    public function __construct()
    {
        $endpoint = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $path = parse_url($endpoint, PHP_URL_PATH) ?? '/';
        if ($path == '/' || $path == '') {
            $endpoint = '/home';
        }

        $normalizedEndpoint = $this->handleEndpoint($endpoint);
        
        if ($normalizedEndpoint == null) {
            return;
        }
        
        $this->controller = $normalizedEndpoint['controller']; 
        $this->method = $normalizedEndpoint['method']; 
        $this->params = $normalizedEndpoint['params'];

        $this->methodType = $method;
    }

    private function handleEndpoint($endpoint)
    {
        $path = parse_url($endpoint, PHP_URL_PATH);
        $path = trim($path, "/");

        $segments = $path == "" ? [] : explode("/", $path);

        if (isset($segments[0]) && $segments[0] == "api") {
            $this->handleApi();
            return null;
        }

        $controller = isset($segments[0]) && $segments[0] != "" ? strtolower($segments[0]) : "home";
        $method = isset($segments[1]) && $segments[1] != "" ? $segments[1] : "index";
        $params = array_slice($segments, 2);

        return [
            'controller' => $controller,
            'method' => $method,
            'params' => $params, 
        ];
    }

    protected function handleApi()
    {
        require_once(__DIR__ . '/../api/routherApi.php');
        $apiRouter = new RoutherApi();
        $apiRouter->exec();
        exit;
    }

    private function normalizeControllerFilename($controller)
    {
        $fileName = strtolower($controller);
        return __DIR__ . "/../controller/" . $fileName . ".php";
    }

    private function normalizeController($controller)
    {
        return ucfirst(strtolower($controller));
    }

    protected function handle404()
    {
        http_response_code(404);

        $viewPath = __DIR__ . '/../view/pageNotFound.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo '404 - Page not found';
        }

        exit;
    }

    public function exec()
    {
        $fileName = $this->normalizeControllerFilename($this->controller);
        if (!file_exists($fileName)) {
            $this->handle404();
        }
        require_once($fileName);

        $controllerName = $this->normalizeController($this->controller);
        if (!class_exists($controllerName)) {
            $this->handle404();
        }

        $classReference = new $controllerName();
        $method = $this->method ?: "index";

        if (!method_exists($classReference, $method)) {
            $this->handle404();
        }

        $reflection = new \ReflectionMethod($classReference, $method);
        $requiredParams = $reflection->getNumberOfRequiredParameters();
        $totalParams = $reflection->getNumberOfParameters();
        $given = count($this->params);

        if ($given < $requiredParams || $given > $totalParams) {
            $this->handle404();
        }

        return call_user_func_array([$classReference, $method], $this->params);
    }
}