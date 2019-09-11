<?php
/**
 * FileName: Auth.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-08-27 21:54
 */

namespace Controllers\Admin;

use Controllers\Controller;

class Auth extends Controller
{
    public function login()
    {
        $key = "example_key";
        $payload = array(
            "data" => [
                'nickname'   => 'admin',
                'headimgurl' => 'http://poci6sbqi.bkt.clouddn.com/avatar.jpg',
                'roles'      => 'admin',
            ],
            "iss"  => "http://example.org",
            "sub"  => "1234567890",
        );
        $token = jwt_encode($payload, $key);

        return $this->response->withContent(json_encode($this->data(['token' => $token])))->send();
    }

    public function user()
    {
//        var_dump($this->request->);
    }

}