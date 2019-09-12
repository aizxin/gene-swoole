<?php
/**
 * FileName: Event.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-09-12 14:34
 */

namespace sf;

use Hyperf\Utils\ApplicationContext;

/**
 * 事件管理类
 * @package sf
 */
class Event
{
    /**
     * 监听者
     * @var array
     */
    protected $listener = [];
    /**
     * 事件别名
     * @var array
     */
    protected $bind = [];
    /**
     * 是否需要事件响应
     * @var bool
     */
    protected $withEvent = true;
    /**
     * 应用对象
     * @var App
     */
    protected $container;

    public function __construct()
    {
        $this->container = ApplicationContext::getContainer();
    }

    /**
     * 设置是否开启事件响应
     * @access protected
     *
     * @param  bool $event 是否需要事件响应
     *
     * @return $this
     */
    public function withEvent(bool $event)
    {
        $this->withEvent = $event;

        return $this;
    }

    /**
     * 批量注册事件监听
     * @access public
     *
     * @param  array $events 事件定义
     *
     * @return $this
     */
    public function listenEvents(array $events)
    {
        if ( ! $this->withEvent) {
            return $this;
        }
        foreach ($events as $event => $listeners) {
            if (isset($this->bind[ $event ])) {
                $event = $this->bind[ $event ];
            }
            $this->listener[ $event ] = array_merge($this->listener[ $event ] ?? [], $listeners);
        }

        return $this;
    }

    /**
     * 注册事件监听
     * @access public
     *
     * @param  string $event    事件名称
     * @param  mixed  $listener 监听操作（或者类名）
     * @param  bool   $first    是否优先执行
     *
     * @return $this
     */
    public function listen(string $event, $listener, $first = false)
    {
        if ( ! $this->withEvent) {
            return $this;
        }
        if (isset($this->bind[ $event ])) {
            $event = $this->bind[ $event ];
        }
        if ($first && isset($this->listener[ $event ])) {
            array_unshift($this->listener[ $event ], $listener);
        } else {
            $this->listener[ $event ][] = $listener;
        }

        return $this;
    }

    /**
     * 是否存在事件监听
     * @access public
     *
     * @param  string $event 事件名称
     *
     * @return bool
     */
    public function hasListen(string $event): bool
    {
        if (isset($this->bind[ $event ])) {
            $event = $this->bind[ $event ];
        }

        return isset($this->listener[ $event ]);
    }

    /**
     * 移除事件监听
     * @access public
     *
     * @param  string $event 事件名称
     *
     * @return $this
     */
    public function remove(string $event): void
    {
        if (isset($this->bind[ $event ])) {
            $event = $this->bind[ $event ];
        }
        unset($this->listener[ $event ]);
    }

    /**
     * 指定事件别名标识 便于调用
     * @access public
     *
     * @param  array $events 事件别名
     *
     * @return $this
     */
    public function bind(array $events)
    {
        $this->bind = array_merge($this->bind, $events);

        return $this;
    }

    /**
     * 触发事件
     * @access public
     *
     * @param  string|object $event  事件名称
     * @param  mixed         $params 传入参数
     * @param  bool          $once   只获取一个有效返回值
     *
     * @return mixed
     */
    public function trigger($event, $params = null, $once = false)
    {
        if ( ! $this->withEvent) {
            return;
        }
        if (is_object($event)) {
            $params = $event;
            $event = get_class($event);
        }
        if (isset($this->bind[ $event ])) {
            $event = $this->bind[ $event ];
        }
        $result = [];
        $listeners = $this->listener[ $event ] ?? [];
        foreach ($listeners as $key => $listener) {
            $result[ $key ] = $this->dispatch($listener, $params);
            if (false === $result[ $key ] || ( ! is_null($result[ $key ]) && $once)) {
                break;
            }
        }

        return $once ? end($result) : $result;
    }

    /**
     * 执行事件调度
     * @access protected
     *
     * @param  mixed $event  事件方法
     * @param  mixed $params 参数
     *
     * @return mixed
     */
    public function dispatch($event, $params = null)
    {
        if ( ! is_string($event)) {
            $call = $event;
        } elseif (strpos($event, '::')) {
            $call = $event;
        } else {
            $obj = $this->container->make($event);
            $call = [$obj, 'handle'];
        }

        return $call($params);
    }
}