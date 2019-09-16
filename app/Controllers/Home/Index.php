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
use Gene\Application;
use Gene\Db\Mysql;
use Gene\Factory;
use Hyperf\Contract\ConfigInterface;

class Index extends Controller
{
    public function index()
    {
        $method = $this->request->getMethod();


        $return = [
            'method'  => $method,
            'data'    => $this->db->select('user')->all(),
        ];

//        $this->db = Factory::create(Mysql::class, [
//            [
//                'dsn'      => 'mysql:dbname=default;host=mysql;port=3306;charset=utf8',
//                'username' => 'root',
//                'password' => 'kfkdock',
//                'options'  => [
//                    \PDO::ATTR_PERSISTENT               => false,
//                    \PDO::MYSQL_ATTR_INIT_COMMAND       => "SET NAMES utf8",
//                    \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
//                ],
//            ],
//        ], true);

        return $this->response($return);
    }
}