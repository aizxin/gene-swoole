<?php
/**
 * FileName: Container.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-08-31 20:41
 */

namespace sf;


use Gene\Di;
use Gene\Factory;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * @param string $name
     */
    public function get($name)
    {
        if ($this->has($name)){
            return Di::get($name);
        }
        return $this->make($name);

    }

    /**
     * @param string $name
     */
    public function has($name)
    {
        return Di::has($name);
    }

    /**
     * @param string $name
     */
    public function make($name,$params = [])
    {
        if ($this->has($name)){
            return Di::get($name);
        }
        $factory = Factory::create($name,$params);
        Di::set($name,$factory);
        return $factory;
    }

    public function __set($name, $value)
    {
        $this->make($name, $value);
    }
    public function __get($name)
    {
        return $this->get($name);
    }
    public function __isset($name)
    {
        return $this->has($name);
    }
    public function __unset($name)
    {
        Di::del($name);
    }
}