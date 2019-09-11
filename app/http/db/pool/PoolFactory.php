<?php
/**
 * FileName: PoolFactory.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-08-31 21:23
 */

namespace sf\db\pool;


use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use sf\Container;

class PoolFactory
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var Channel[]
     */
    protected $pools = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getPool(string $name): DbPool
    {
        if (isset($this->pools[ $name ])) {
            return $this->pools[ $name ];
        }
        if ($this->container instanceof Container) {
            $pool = $this->container->make(DbPool::class, ['container' => $this->container, 'name' => $name]);
        } else {
            $pool = new DbPool($this->container, $name);
        }

        return $this->pools[ $name ] = $pool;
    }
}