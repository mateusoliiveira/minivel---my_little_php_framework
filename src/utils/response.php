<?php

class JsonResponse
{
    public static function send(array $data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
