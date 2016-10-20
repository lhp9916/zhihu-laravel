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

    //获取用户信息
    public function read()
    {
        $id = rq('id');
        if (!$id) {
            return error('id不存在');
        }
        if ($id === 'self') {
            if (!is_logged_in()) {
                return error('请先登陆');
            }
            $id = session('user_id');
        } else {
            $id = rq('id');
        }
        $get = ['username', 'avatar_url', 'intro'];
        $user = $this->find($id, $get);
        if (!$user) {
            return error('用户不存在');
        }
        $data = $user->toArray();
        $answer_count = get_answer_instance()->where('user_id', $id)->count();
        $question_count = get_question_instance()->where('user_id', $id)->count();
        $data['answer_count'] = $answer_count;
        $data['question_count'] = $question_count;
        return success($data);
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
        return is_logged_in();
    }

    public function answer()
    {
        return $this
            ->belongsToMany('App\Answer')
            ->withPivot('vote')
            ->withTimestamps();
    }

    public function questions()
    {
        return $this
            ->belongsToMany('App\Question')
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

    //找回密码
    public function reset_password()
    {
        if ($this->is_robot()) {
            return error('操作过于频繁，请稍后再试');
        }

        $phone = rq('phone');
        if (!$phone) {
            return error('手机号不能为空');
        }
        $user = $this->where('phone', $phone)->first();
        if (!$user) {
            return error('用户不存在');
        }

        //模拟发送短信
        $captcha = $this->generate_captcha();
        $user->phone_captcha = $captcha;
        if (!$user->save()) {
            return error('数据库操作失败');
        }
        $this->send_sms();
        session()->set('last_sms_time', time());
        return success(['msg' => '短信发送成功']);
    }

    //随机生成验证码
    public function generate_captcha()
    {
        return rand(1000, 9999);
    }

    //验证找回密码
    public function validate_reset_password()
    {
        if ($this->is_robot(2)) {
            return error('操作过于频繁，请稍后再试');
        }

        $phone = rq('phone');
        $phone_captcha = rq('phone_captcha');
        $new_password = rq('new_password');
        if (!$phone || !$phone_captcha || !$new_password) {
            return error('phone phone_captcha new_password 都不能为空');
        }
        $user = $this->where([
            'phone' => $phone,
            'phone_captcha' => $phone_captcha,
        ])->first();
        if (!$user) {
            return error('验证码不正确');
        }
        $user->password = bcrypt($new_password);
        if (!$user->save()) {
            return error('数据库写入失败');
        }
        session()->set('last_sms_time', time());
        return success(['msg' => '更改密码成功']);

//        简化写法
//        return $user->save() ? success() : error('数据库写入失败');
    }

    //判断是否为机器人
    public function is_robot($time = 10)
    {
        //session没有last_sms_time,接口没有被调用
        if (!session('last_sms_time')) {
            return false;
        }
        $current_time = time();
        $last_sms_time = session('last_sms_time');
        if ($current_time - $last_sms_time > $time) {
            return false;
        }
        return true;
    }

    //模拟发送短信  具体的方式根据业务来写
    public function send_sms()
    {
        return true;
    }

    public function exists()
    {
        return success(['count' => $this->where(rq())->count()]);
    }
}
