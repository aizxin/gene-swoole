<?php
/**
 * FileName: DbConnection.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-08-31 21:25
 */

namespace sf\db;


use Gene\Db\Mysql;
use Hyperf\Contract\ConnectionInterface;
use Hyperf\Pool\Connection;
use Hyperf\Pool\Exception\ConnectionException;
use Hyperf\Pool\Pool;
use Psr\Container\ContainerInterface;

class DbConnection extends Connection implements ConnectionInterface
{
    /**
     * @var Manager
     */
    protected $connection;
    /**
     * @var array
     */
    protected $config;
    public function __construct(ContainerInterface $container, Pool $pool, array $config)
    {
        parent::__construct($container, $pool);
        $this->config = $config;
        $this->reconnect();
    }
    public function getActiveConnection()
    {
        // TODO: Implement getActiveConnection() method.
        if ($this->check()) {
            return $this;
        }
        if (!$this->reconnect()) {
            throw new ConnectionException('Connection reconnect failed.');
        }
        return $this;
    }

    public function getDb()
    {
        if ($this->check()) {
            return $this->connection;
        }
        if (!$this->reconnect()) {
            throw new ConnectionException('Connection reconnect failed.');
        }
        return $this->connection;
    }

    /**
     * Reconnect the connection.
     */
    public function reconnect(): bool
    {
        // TODO: Implement reconnect() method.
        try {
            $this->connection = new Mysql($this->config);
        } catch (\Exception $e) {
            throw new ConnectionException('mysql 连接参数错误:' . $e->getMessage());
        } catch (\RuntimeException $e) {
            throw new ConnectionException('mysql uri格式错误:' . $e->getMessage());
        }
        $this->lastUseTime = microtime(true);
        return true;
    }
    /**
     * Close the connection.
     */
    public function close(): bool
    {
        // TODO: Implement close() method.
        return true;
    }


}