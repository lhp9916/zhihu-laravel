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

    public function login()
    {
        $username = \Request::get('username');
        $password = \Request::get('password');
        if (!$username) {
            return ['status' => 0, 'msg' => '用户名不可为空'];
        }
        if (!$password) {
            return ['status' => 0, 'msg' => '密码不可为空'];
        }
        $user = $this->where('username', $username)->first();
        if (!$user) {
            return ['status' => 0, 'msg' => '用户不存在'];
        }
        $hashed_password = $user->password;
        if (!\Hash::check($password, $hashed_password)) {
            return ['status' => 0, 'msg' => '密码错误'];
        }
        //登录成功，保存至session
        session()->put('username', $user->username);
        session()->put('user_id', $user->id);
//        dd(session()->all());
        return ['status' => 1, 'id' => $user->id];
    }

    public function logout()
    {
//        session()->flush();//清空session所有内容
        session()->forget('username');
        session()->forget('user_id');
//        dd(session()->all());
        return ['status' => 1, 'msg' => '登出成功'];
    }

    public function is_logged_in()
    {
        return session('user_id') ?: false;
    }

    public function answer()
    {
        return $this
            ->belongsToMany('App\Answer')
            ->withPivot('vote')
            ->withTimestamps();
    }

    public function change_password()
    {
        if (!$this->is_logged_in()) {
            return error('请先登录');
        }
        if (!rq('old_password') || !rq('new_password')) {
            return error('old_password new_password 缺一不可');
        }
        $user = $this->find(session('user_id'));
        if (!\Hash::check(rq('old_password'), $user->password)) {
            return error('原始密码错误');
        }

        $user->password = bcrypt(rq('new_password'));
        if ($user->save()) {
            return success(['msg' => '保存成功']);
        }
        return error('数据库操作失败');
    }

    //重置密码
    public function reset_password()
    {
        $phone = rq('phone');
        if (!$phone) {
            return error('手机号不能为空');
        }
        $user = $this->where('phone', $phone)->first();
        if (!$user) {
            return error('手机号不存在');
        }

        //模拟发送短信
        $captcha = $this->generate_captcha();
        $user->phone_captcha = $captcha;
        if (!$user->save()) {
            return error('数据库操作失败');
        }
        $this->send_sms();
        return success(['msg' => '短信发送成功']);
    }

    //随机生成验证码
    public function generate_captcha()
    {
        return rand(1000, 9999);
    }

    //模拟发送短信  具体的方式根据业务来写
    public function send_sms()
    {
        return true;
    }
}
