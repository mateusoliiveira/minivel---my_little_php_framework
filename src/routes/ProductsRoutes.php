<?php

require(__DIR__ . '/../controllers/ProductsController.php');
class JsonResponse
{
    public static function send(array $data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

/**
 * Summary of ProductsRouter
 */
class ProductsRouter
{
    /**
     * Summary of uri
     * @var
     */
    public $uri;
    public $method;
    public $controller;
    protected $instance;

    public function __construct()
    {
        $this->controller = new ProductsController();
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->handleRoute();
    }

    public function handleRoute()
    {
        $splitted = explode('/', $this->uri);
        $checkLength = count($splitted);

        if (preg_match('/products/', $this->uri) == 1) {
            if ($this->method === 'GET') {
                if ($checkLength == 2) {
                    return JsonResponse::send([
                        'data' => $this->controller->index(),
                        'message' => 'ok'
                    ]);
                }
                if ($checkLength == 3) {
                    return JsonResponse::send([
                        'data' => $this->controller->show($splitted[2]),
                        'message' => 'ok'
                    ]);
                }
                return JsonResponse::send([[], 'Método não permitido', 'status' => 405]);
            }
            if ($this->method === 'POST') {
                return JsonResponse::send([[], 'Método não permitido', 'status' => 405]);
            }
            return JsonResponse::send([[], 'message' => 'Página não encontrada', 'status' => 404]);
        }
    }

    protected function app()
    {
        if (!$this->instance) {
            $this->instance = new ProductsRouter();
            return $this->instance;
        }

        return $this->instance;
    }
}
