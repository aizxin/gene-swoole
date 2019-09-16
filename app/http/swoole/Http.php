<?php
/**
 * FileName: Http.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-06-27 13:06
 */

namespace sf\swoole;

use Gene\Application;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\ApplicationContext;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use sf\Event;
use sf\Log;
use Swoole\Runtime;
use Swoole\WebSocket\Server as WebSocketServer;
use Swoole\Http\Server as HttpServer;
use Swoole\Server as SwooleServer;


use Hyperf\HttpMessage\Server\Request as Psr7Request;
use Hyperf\HttpMessage\Server\Response as Psr7Response;
use Hyperf\Utils\Context;


class Http extends Server
{
    protected $lastMtime;
    protected $option = [];
    protected $app;
    protected $container;
    protected $event;
    protected $log;

    /**
     * 架构函数
     * @access public
     */
    public function __construct($app = null)
    {
        $this->app = $app;
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig($config = [])
    {
        $this->option = array_merge($this->option, $config);

        return $this;
    }

    /**
     * @return mixed
     * @author: kong | <iwhero@yeah.com>
     */
    public function getSwoole()
    {
        $this->run();

        $this->container = ApplicationContext::getContainer();
        $this->event = $this->container->make(Event::class);

        $config = $this->container->get(ConfigInterface::class);

        $this->event->bind($config->get('bind') ?? [])
            ->listenEvents($config->get('listener') ?? [])
            ->trigger('swoole.Process', $this->swoole);

        $this->container->make(Application::class, $this->app);

        $this->log = $this->container->make(Log::class);

        return $this->swoole;
    }

    private function run()
    {
        $host = $this->option['host'] ?? $this->host;
        $port = $this->option['port'] ?? $this->port;
        $mode = $this->option['mode'] ?? $this->mode;
        $sockType = $this->option['sockType'] ?? $this->sockType;
        switch ($this->option['server_type'] ?? '') {
            case 'websocket':
                $this->swoole = new WebSocketServer($host, $port, $mode, $sockType);
                break;
            default:
                $this->swoole = new HttpServer($host, $port, $mode, $sockType);
        }
        $this->setOption($this->option);

        // 开启 协程
        if ($this->option['enable_coroutine'] ?? false) {
            Runtime::enableCoroutine(true);
        }
    }

    private function setOption($option = [])
    {
        // 设置参数
        if ( ! empty($option)) {
            $this->swoole->set($option);
        }

        foreach ($this->eventOn as $event) {
            // 自定义回调
            if ( ! empty($option[ $event ])) {
                $this->swoole->on($event, $option[ $event ]);
            } elseif (method_exists($this, 'on' . $event)) {
                $this->swoole->on($event, [$this, 'on' . $event]);
            }
        }
    }

    /**
     * @param $server
     */
    public function onStart($server)
    {
        @swoole_set_process_name("swf-server");
    }

    /**
     * 此事件在Worker进程/Task进程启动时发生,这里创建的对象可以在进程生命周期内使用
     *
     * @param $server
     * @param $worker_id
     */
    public function onWorkerStart($server, $worker_id)
    {
        $this->lastMtime = time();
    }


    /**
     * peceive回调
     *
     * @param $server
     * @param $fd
     * @param $reactor_id
     * @param $data
     */
    public function onReceive($server, $fd, $reactor_id, $data)
    {

    }


    /**
     * request回调
     *
     * @param $request
     * @param $response
     */
    public function onRequest($request, $response)
    {
        $method = $request->server['request_method'] ?? 'GET';
        $uri = $request->server['request_uri'] ?? '/';

        $response->header('Content-Type', 'application/json; charset=utf-8');
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Methods', '*');
        $response->header('Access-Control-Allow-Credentials', 'true');
        $response->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        if ($method == 'OPTIONS' || $uri == '/favicon.ico') {
            $response->status(40400);

            return $response->end(400);
        }

        Context::set(RequestInterface::class, Psr7Request::loadFromSwooleRequest($request));
        Context::set(ResponseInterface::class, new Psr7Response($response));

        \Gene\Request::init(
            $request->get,
            $request->post,
            $request->cookie,
            $request->server,
            ['fd' => $request->fd],
            $request->files);

        try {
            $this->app->run($method, $uri);
        } catch (\Exception $exception) {
            $this->log->error($exception->getMessage());

            return $response->end(400);
        } catch (\Throwable $exception) {
            $this->log->error($exception->getMessage());

            return $response->end(400);
        }
    }

    /**
     * onOpen回调
     *
     * @param $server
     * @param $frame
     */
    public function onOpen($server, $request)
    {

    }

    /**
     * Message回调
     *
     * @param $server
     * @param $frame
     */
    public function onMessage($server, $frame)
    {

    }

    /**
     * Close回调
     *
     * @param $server
     * @param $frame
     */
    public function onClose($server, $fd)
    {

    }

    /**
     * 任务投递
     *
     * @param HttpServer $serv
     * @param            $task_id
     * @param            $fromWorkerId
     * @param            $data
     *
     * @return mixed|null
     */
    public function onTask(SwooleServer $serv, $taskId, $srcWorkerId, $data)
    {
        $this->container->get(TaskCallback::class)->onTask($serv, $taskId, $srcWorkerId, $data);
    }

    /**
     * 任务结束，如果有自定义任务结束回调方法则不会触发该方法
     *
     * @param HttpServer $serv
     * @param            $task_id
     * @param            $data
     */
    public function onFinish(SwooleServer $serv, $task_id, $data)
    {
        $this->container->get(FinishCallback::class)->onFinish($serv, $task_id, $data);
    }

    /**
     * 当Worker/Task进程发生异常后会在Manager进程内回调此函数。
     *
     * @param SwooleServer $serv
     * @param              $worker_id
     * @param              $worker_pid
     * @param              $exit_code
     * @param              $signal
     *
     * @author: kong | <iwhero@yeah.com>
     * @date  : 2019-08-28 13:12
     */
    public function onWorkerError(SwooleServer $serv, $worker_id, $worker_pid, $exit_code, $signal)
    {
        var_dump($exit_code);
    }
}