<?php
/**
 * FileName: DbPool.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-08-31 21:24
 */

namespace sf\db\pool;


use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ConnectionInterface;
use Hyperf\Pool\Pool;
use Hyperf\Utils\Arr;
use Psr\Container\ContainerInterface;
use sf\db\DbConnection;

class DbPool extends Pool
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var array
     */
    protected $config;
    public function __construct(ContainerInterface $container, string $name)
    {
        $this->name = $name;
        $config = $container->get(ConfigInterface::class);
        $key = sprintf('mysql.%s', $this->name);
        $this->config = $config->get($key);
        if (!$this->config) {
            throw new \InvalidArgumentException(sprintf('config[%s] is not exist!', $key));
        }
        $options = Arr::get($this->config, 'pool', []);
        parent::__construct($container, $options);
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    protected function createConnection(): ConnectionInterface
    {
        return new DbConnection($this->container, $this, $this->config);
    }
}