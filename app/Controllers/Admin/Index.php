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

namespace Controllers\Admin;

use Controllers\Controller;
use Gene\Factory;
use sf\db\Db;

class Index extends Controller
{
    public function index()
    {
        $db = Factory::create(Db::class);
        var_dump($db->getDb()->select('user')->all());
        return $this->response->withContent("Admin")->send();
    }
}