<?php
/**
 * FileName: router.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-08-28 12:42
 */

//实例化路由对象
$router = new \Gene\Router();
//配置路由
$router->clear()
    ->get("/", function ($params = '') {
        echo "index";
    })
    ->get("/admin", function ($params = '') {
        echo "admin";
    })
    ->error(401, function ($params = '') {
        echo 401;
    })
    ->error(404, function ($params = '') {
        echo 122;
    });

$router::runError(404);
//   $router->run('get', $argv[1] ?? '/');

//
//class Route extends HTTPRouter {
//    public function get($pattern, $action) {
//        return $this->method('GET', $pattern, $action);
//    }
//    public function post($pattern, $action) {
//        return $this->method('POST', $pattern, $action);
//    }
//}
//
//$router = new Route('/custom-uri/sub/99/', 'GET');
//
//$router->group('custom-uri', function() use ($router) {
//    echo 1;
//    $router->group('sub', function() use ($router) {
//        echo 2;
//        $router->get('{id}', function($id) {
//            return $id;
//        });
//    });
//});
//
//echo $router->action();

//$http = new Swoole\Http\Server("127.0.0.1", 9501);
//
//$http->on("start", function ($server) {
//    echo "Swoole http server is started at http://127.0.0.1:9501\n";
//});
//
//$http->on("request", function ($request, $response) use ($router) {
//    var_dump($router->run('get', $request->server['request_uri']));
//    $response->header("Content-Type", "text/plain");
//    $response->end("Hello World\n");
//});
//
//$http->start();
