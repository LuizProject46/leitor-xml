<?php

use FastRoute\RouteCollector;

require_once 'uploader.php';

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('POST', '/upload', 'uploadFile');
});

function handleRequest($dispatcher, string $request_method, string $request_uri)
{
    list($code, $handler, $vars) = $dispatcher->dispatch($request_method, $request_uri);

    switch ($code) {
        case FastRoute\Dispatcher::NOT_FOUND:
            $result = [
                'status' => 404,
                'message' => 'Not Found',
                'errors' => [
                    sprintf('The URI "%s" was not found', $request_uri)
                ]
            ];
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $handler;
            $result = [
                'status' => 405,
                'message' => 'Method Not Allowed',
                'errors' => [
                    sprintf('Method "%s" is not allowed', $request_method)
                ]
            ];
            break;
        case FastRoute\Dispatcher::FOUND:
            $result = call_user_func($handler, $vars);
            break;
    }

    return $result;
}

?>