<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //注册
    public function signup()
    {
        $username = \Request::get('username');
        $password = \Request::get('password');
        if (!$username) {
            return ['status' => 0, 'msg' => '用户名不可为空'];
        }
        if (!$password) {
            return ['status' => 0, 'msg' => '密码不可为空'];
        }
        $user_exists = $this->where('username', $username)->exists();
        if ($user_exists) {
            return ['status' => 0, 'msg' => '用户已存在'];
        }
        $hashed_password = bcrypt($password);
        $user = $this;
        $user->password = $hashed_password;
        $user->username = $username;
        if ($user->save()) {
            return ['status' => 1, 'id' => $user->id];
        } else {
            return ['status' => 0, 'msg' => '注册失败'];
        }

    }
}
