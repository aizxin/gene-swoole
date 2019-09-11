<?php
/**
 * FileName: Db.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-08-31 21:16
 */

namespace sf\db;

use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use sf\db\pool\PoolFactory;

class Db
{
    /**
     * @var PoolFactory
     */
    protected $factory;
    /**
     * @var string
     */
    protected $poolName = 'default';

    protected $container;

    public function __construct()
    {
        $this->container = ApplicationContext::getContainer();
        $this->factory = $this->container->make(PoolFactory::class, ['container' => $this->container]);
    }

    public function __call($name, $arguments)
    {
        return $this->getDb()->{$name}(...$arguments);
    }

    public function getDb()
    {
        return $this->getConnection()->getDb();
    }

    public function getPdo()
    {
        return $this->getDb()->getPdo();
    }

    private function getConnection()
    {
        $connection = null;
        $hasContextConnection = Context::has($this->getContextKey());
        if ($hasContextConnection) {
            $connection = Context::get($this->getContextKey());
        }
        if ( ! $connection instanceof DbConnection) {
            $pool = $this->factory->getPool($this->poolName);
            $connection = $pool->get()->getConnection();
            // Should storage the connection to coroutine context, then use defer() to release the connection.
            Context::set($this->getContextKey(), $connection);
            defer(function () use ($connection) {
                $connection->release();
            });
        }

        return $connection;
    }

    /**
     * The key to identify the connection object in coroutine context.
     */
    private function getContextKey(): string
    {
        return sprintf('db.connection.%s', $this->poolName);
    }
}