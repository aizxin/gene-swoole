<?php
$config = new \gene\config();
$config->clear();
\Gene\Di::set(\Hyperf\Contract\ConfigInterface::class, $config);

$config->set("app", [
    'log_path' => BASE_PATH . "/runtime",
]);

$config->set("server", [
    "host"                  => "0.0.0.0"
    , "port"                => 9501
    , "server_type"         => ""
    , "pid_file"            => BASE_PATH . "/runtime/swoole_server.pid"
    , "log_file"            => BASE_PATH . "/runtime/swoole_http_server.log"
    , 'enable_coroutine'    => true,
    'worker_num'            => swoole_cpu_num(),
    'open_tcp_nodelay'      => true,
    'max_coroutine'         => 100000,
    'open_http2_protocol'   => true,
    'max_request'           => 100000,
    'socket_buffer_size'    => 2 * 1024 * 1024,
    // Task Worker 数量，根据您的服务器配置而配置适当的数量
    'task_worker_num'       => swoole_cpu_num(),
    // 因为 `Task` 主要处理无法协程化的方法，所以这里推荐设为 `false`，避免协程下出现数据混淆的情况
    'task_enable_coroutine' => false,
    'daemonize'             => false,
]);

$config->set("mysql.default", [
    'dsn'      => 'mysql:dbname=default;host=mysql;port=3306;charset=utf8',
    'username' => 'root',
    'password' => 'kfkdock',
    'options'  => [
//        \PDO::ATTR_CASE              => \PDO::CASE_NATURAL,
//        \PDO::ATTR_ERRMODE           => \PDO::ERRMODE_EXCEPTION,
//        \PDO::ATTR_ORACLE_NULLS      => \PDO::NULL_NATURAL,
//        \PDO::ATTR_STRINGIFY_FETCHES => false,
//        \PDO::ATTR_EMULATE_PREPARES  => false,
    ],
    'pool'     => [
        'min_connections' => 1,
        'max_connections' => 10,
        'connect_timeout' => 10.0,
        'wait_timeout'    => 3.0,
        'heartbeat'       => -1,
        'max_idle_time'   => 60,
    ],
]);

$config->set("redisconnection.default", [
    'persistent' => true,
    'host'       => 'redis',
    'port'       => 6379,
    'timeout'    => 3,
    'ttl'        => 0,
    'pool'       => [
        'min_connections' => 1,
        'max_connections' => 10,
        'connect_timeout' => 10.0,
        'wait_timeout'    => 3.0,
        'heartbeat'       => -1,
        'max_idle_time'   => 60,
    ],
]);
// orm注入配置
$config->set("db", [
    'class'    => \sf\db\Db::class,
    'params'   => [],
    'instance' => true,
]);
// 缓存类注入配置
$config->set("redis", [
    'class'    => \sf\redis\RedisServer::class,
    'params'   => [
        'poolName' => 'default',
    ],
    'instance' => false,
]);

//框架方法级缓存模块注入配置
$config->set("cache", [
    'class'    => \Gene\Cache\Cache::class,
    'params'   => [[
        'hook'        => 'redis',
        'sign'        => 'web:',
        'versionSign' => 'database:',
    ]],
    'instance' => false,
]);

// 注入log
$config->set("log", [
    'class'    => \sf\Log::class,
    'params'   => [],
    'instance' => false,
]);

// 监听
$config->set("listener", [
    'swoole.Process' => [
        \sf\listener\TestListener::class,
    ],
]);

