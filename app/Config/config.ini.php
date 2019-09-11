<?php
$config = new \gene\config();
$config->clear();
\Gene\Di::set(\Hyperf\Contract\ConfigInterface::class, $config);

$config->set("server", [
    "host"                       => "0.0.0.0"
    , "port"                     => 9501
    , "server_type"              => ""
    , "worker_num"               => 4
    , "task_worker_num"          => 4
    , "daemonize"                => false
    , "dispatch_mode"            => 3
    , "open_tcp_nodelay"         => true
    , "pid_file"                 => BASE_PATH . "/runtime/swoole_server.pid"
    , "log_file"                 => BASE_PATH . "/runtime/swoole_http_server.log"
    , "heartbeat_check_interval" => 660
    , "heartbeat_idle_time"      => 1200
    , "trace_event_worker"       => true
    , "request_slowlog_timeout"  => 1
    , "document_root"            => BASE_PATH . "/public"
    , "enable_static_handler"    => true
    , "enable_coroutine"         => true
    , "task_enable_coroutine"    => false
    , "max_request"              => 100000
    , "max_coroutine"            => 100000
    , "open_http2_protocol"      => true
    , "http_compression"         => true,
]);

$config->set("mysql.default", [
    'dsn'      => 'mysql:dbname=default;host=mysql;port=3306;charset=utf8',
    'username' => 'root',
    'password' => 'kfkdock',
    'options'  => [\PDO::ATTR_PERSISTENT => true],
    'pool'     => [
        'min_connections' => 1,
        'max_connections' => 10,
        'connect_timeout' => 10.0,
        'wait_timeout'    => 3.0,
        'heartbeat'       => -1,
        'max_idle_time'   => 60,
    ],
]);
$config->set("db", [
    'class'    => '\sf\db\Db',
    'params'   => [],
    'instance' => true,
]);

