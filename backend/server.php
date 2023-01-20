<?php
require_once __DIR__ . '/vendor/autoload.php';

use OpenSwoole\Http\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

use FastRoute\RouteCollector;

require_once __DIR__ . "/routes/routes.php";

$server = new OpenSwoole\HTTP\Server("0.0.0.0", 9501);

$server->on("Start", function(Server $server)
{
    echo "OpenSwoole http server is started at http://127.0.0.1:9501\n";
});

$server->on("Request", function(Request $request, Response $response)
{
    global $dispatcher;

    $request_method = $request->server['request_method'];
    $request_uri = $request->server['request_uri'];

    $_SERVER['REQUEST_URI'] = $request_uri;
    $_SERVER['REQUEST_METHOD'] = $request_method;
    $_SERVER['REMOTE_ADDR'] = $request->server['remote_addr'];

    $_GET = $request->get ?? [];
    $_FILES = $request->files ?? [];

    if ($request_method === 'POST' && $request->header['content-type'] === 'application/json') {
        $body = $request->rawContent();
        $_POST = empty($body) ? [] : json_decode($body);
    } else {
        $_POST = $request->post ?? [];
    }


    // global content type for our responses
    $response->header('Content-Type', 'application/json');
    $response->header('Access-Control-Allow-Origin', '*');
    $response->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, PATCH, DELETE');
    $response->header('Access-Control-Allow-Headers', '*');
    $response->header('Access-Control-Allow-Credentials', 'true');

    $result = handleRequest($dispatcher, $request_method, $request_uri);

    $response->end(json_encode($result));
});

$server->start();
