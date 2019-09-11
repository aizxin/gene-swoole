<?php
/**
 * FileName: Index.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-08-24 23:12
 */

namespace Controllers\Home;

use Controllers\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->successResponse();
    }
}