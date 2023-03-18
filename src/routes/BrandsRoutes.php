<?php

require(__DIR__ . '/../controllers/BrandsController.php');
class JsonResponse
{
    public static function send(array $data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

/**
 * Summary of BrandsRouter
 */
// class BrandsRouter
// {
//     /**
//      * Summary of uri
//      * @var
//      */
//     public $uri;
//     public $method;
//     public $controller;
//     protected $instance;

//     public function __construct()
//     {
//         $this->controller = new BrandsController();
//         $this->uri = $_SERVER['REQUEST_URI'];
//         $this->method = $_SERVER['REQUEST_METHOD'];
//         $this->handleRoute();
//     }

//     public function handleRoute()
//     {

//         $splitted = explode('/', $this->uri);
//         $checkLength = count($splitted);

//         if (preg_match('/products/', $this->uri) == 1) {
//             if ($this->method === 'GET') {
//                 if ($checkLength == 2) {
//                     return JsonResponse::send([
//                         'data' => $this->controller->index(),
//                         'message' => 'ok'
//                     ]);
//                 }
//                 if ($checkLength == 3) {
//                     return JsonResponse::send([
//                         'data' => $this->controller->show($splitted[2]),
//                         'message' => 'ok'
//                     ]);
//                 }
//                 return JsonResponse::send([[], 'Método não permitido', 'status' => 405]);
//             }
//             if ($this->method === 'POST') {
//                 return JsonResponse::send([[], 'Método não permitido', 'status' => 405]);
//             }
//             return JsonResponse::send([[], 'message' => 'Página não encontrada', 'status' => 404]);
//         }
//     }

//     protected function app()
//     {
//         if (!$this->instance) {
//             $this->instance = new BrandsRouter();
//             return $this->instance;
//         }

//         return $this->instance;
//     }
// }

class Router
{
    private $routes = array();

    public function add($pattern, $callback, $method = 'GET')
    {
        $route = array(
            'pattern' => $pattern,
            'callback' => $callback,
            'method' => $method
        );
        array_push($this->routes, $route);
    }

    public function run($url, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] != $method) continue;

            $pattern = $route['pattern'];
            $pattern = str_replace('/', '\/', $pattern);
            preg_match_all('/:([^\/]+)/', $pattern, $matches);
            $param_names = $matches[1];

            $pattern = preg_replace('/:([^\/]+)/', '([^\/]+)', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches);

                $params = array();
                foreach ($param_names as $index => $name) {
                    $params[$name] = $matches[$index];
                }

                $callback = $route['callback'];
                $callback($params, $_GET);
                return;
            }
        }

        header('HTTP/1.1 404 Not Found');
    }
}

$router = new Router();

// Define your routes here
$router->add('/brands', function ($params, $query_params) {
    $controller = new BrandsController();
    return JsonResponse::send([
        'data' => $controller->index(),
        'message' => 'ok'
    ]);
});
$router->add('/brands/:id', function ($params, $query_params) {
    $controller = new BrandsController();
    return JsonResponse::send([
        'data' => $controller->show($params['id']),
        'message' => 'ok'
    ]);
});
// Run the router
$router->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
