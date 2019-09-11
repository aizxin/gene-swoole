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
        $payload = [
            'nickname'   => 'admin',
            'headimgurl' => 'http://poci6sbqi.bkt.clouddn.com/avatar.jpg',
            'roles'      => 'admin',
        ];
        $token = jwt_encode($payload, $key);

        return $this->response(['token' => $token]);
    }

    public function user()
    {
        $prefix = 'Bearer';
        $token = $this->request->getHeader('authorization')[0] ?? '';
        $arr = explode($prefix . ' ', $token);
        $token = $arr[1] ?? '';
        $hmackey = "example_key";
        try {
            $decoded_token = jwt_decode($token, $hmackey);

            return $this->response($decoded_token);
        } catch (\Exception $exception) {
            return $this->errorResponse('验证错误，请重新登录');
        }

    }

}