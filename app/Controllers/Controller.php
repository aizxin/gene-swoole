<?php
/**
 * FileName: Controller.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-08-27 21:51
 */

namespace Controllers;

use Hyperf\Utils\Context;

class Controller extends \Gene\Controller
{
    protected $request;
    protected $response;

    public function __construct()
    {
        $this->response = Context::get('response');
        $this->request = Context::get('request');
    }

    public function error401()
    {
        var_dump($this->request->getParsedBody());
        return $this->response->getSwooleResponse()->end('401');
    }

    public function error404()
    {
        return $this->response->getSwooleResponse()->end('404');
    }
}