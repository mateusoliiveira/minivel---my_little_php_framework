<?php

require(__DIR__ . '/../utils/response.php');
require(__DIR__ . '/../controllers/BrandsController.php');

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

var_dump($_POST);
$router = new Router();


// Define your routes here
$router->add('/brands/:id', function ($params, $query_params) {
    $repository = new BrandsRepository();
    $controller = new BrandsController($repository);
    return JsonResponse::send([
        'data' => $controller->destroy($params['id']),
        'message' => 'ok'
    ]);
}, 'DELETE');
$router->add('/brands', function ($params, $query_params) {
    header('Accept: application/json');
    $json_str = json_decode(file_get_contents('php://input'));
    $data = array(
        'name' => $json_str->name,
        'picture' => $json_str->picture,
    );
    $repository = new BrandsRepository();
    $controller = new BrandsController($repository);
    return JsonResponse::send([
        'data' => $controller->store($data),
        'message' => 'ok'
    ]);
}, 'POST');
$router->add('/brands', function ($params, $query_params) {
    header('Accept: application/json');
    $json_str = json_decode(file_get_contents('php://input'));
    $data = array(
        'id' => $json_str->id,
        'name' => $json_str->name,
        'picture' => $json_str->picture,
    );
    $repository = new BrandsRepository();
    $controller = new BrandsController($repository);
    return JsonResponse::send([
        'data' => $controller->update($data),
        'message' => 'ok'
    ]);
}, 'PUT');
$router->add('/brands', function ($params, $query_params) {
    $repository = new BrandsRepository();
    $controller = new BrandsController($repository);
    return JsonResponse::send([
        'data' => $controller->index(),
        'message' => 'ok'
    ]);
});
$router->add('/brands/:id', function ($params, $query_params) {
    $repository = new BrandsRepository();
    $controller = new BrandsController($repository);
    return JsonResponse::send([
        'data' => $controller->show($params['id']),
        'message' => 'ok'
    ]);
});
// Run the router
$router->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
