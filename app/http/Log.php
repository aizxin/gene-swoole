<?php
/**
 * FileName: Log.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-09-12 20:49
 */

namespace sf;


use Hyperf\Contract\ConfigInterface;

class Log
{
    protected $log;

    public function __construct()
    {
        $config = make(ConfigInterface::class);
        $this->log = make(\SeasLog::class);
        $this->log->setLogger('log/'.date('Ym'));
        $this->log->setBasePath($config->get('app.log_path') ?? BASE_PATH . '/runtime');
    }

    public function __call($name, $arguments)
    {
        return $this->log->{$name}(...$arguments);
    }

    public function setLogger($path = 'log'){
        $this->log->setLogger($path.'/'.date('Ym'));
        return $this;
    }
}