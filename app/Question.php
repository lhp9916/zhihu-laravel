<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function add()
    {
        if (!get_user_instance()->is_logged_in()) {
            return ['status' => 0, 'msg' => '请先登录'];
        }
        if (!rq('title')) {
            return ['status' => 0, 'msg' => '标题不能为空'];
        }
        $this->title = rq('title');
        if (rq('desc')) {
            $this->desc = rq('desc');
        }
        $this->user_id = session('user_id');
        if ($this->save()) {
            return ['status' => 1, 'id' => $this->id];
        }
        return ['status' => 0, 'msg' => '保存失败'];
    }

    public function change()
    {
        if (!get_user_instance()->is_logged_in()) {
            return ['status' => 0, 'msg' => '请先登录'];
        }
        $id = rq('id');
        if (!$id) {
            return ['status' => 0, 'msg' => 'id不能为空'];
        }
        $question = $this->find($id);//返回主键所在行
        if (!$question) {
            return ['status' => 0, 'msg' => '问题不存在'];
        }
        if ($question->user_id != session('user_id')) {
            return ['status' => 0, 'msg' => '您没有权限修改'];
        }

        if (rq('title')) {
            $question->title = rq('title');
        }
        if (rq('desc')) {
            $question->desc = rq('desc');
        }
        if ($question->save()) {
            return ['status' => 1, 'msg' => '更新成功'];
        }
        return ['status' => 0, 'msg' => '更新失败'];
    }

    public function read_by_user_id($id)
    {
        $user = get_user_instance()->find($id);
        if (!$user) {
            return error("用户不存在");
        }
        $rs = $this->where('user_id', $id)->get()->keyBy('id');
        return success($rs->toArray());
    }


    public function read()
    {
        $id = rq('id');
        if ($id) {
            $question = $this->with('answers_with_user_info')->find($id);
            if (!$question) {
                return ['status' => 0, 'msg' => '问题不存在'];
            }
            return ['status' => 1, 'data' => $question];
        }

        if (rq('user_id')) {
            $user_id = rq('user_id') === 'self' ? session('user_id') : rq('user_id');
            return $this->read_by_user_id($user_id);
        }

        //批量读取
        list($limit, $skip) = pagenate(rq('page'), rq('limit'));
        $res = $this
            ->orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get(['id', 'title', 'user_id', 'created_at', 'updated_at'])
            ->keyBy('id');//以id为key
        return ['status' => 1, 'data' => $res];
    }

    public function remove()
    {
        if (!get_user_instance()->is_logged_in()) {
            return ['status' => 0, 'msg' => '请先登录'];
        }
        $id = rq('id');
        if (!$id) {
            return ['status' => 0, 'msg' => 'id不能为空'];
        }
        $question = $this->find($id);//返回主键所在行
        if (!$question) {
            return ['status' => 0, 'msg' => '问题不存在'];
        }
        //所有者才能删除
        if ($question->user_id != session('user_id')) {
            return ['status' => 0, 'msg' => '您没有权限修改'];
        }
        if ($question->delete()) {
            return ['status' => 1, 'msg' => '删除成功'];
        }
        return ['status' => 0, 'msg' => '删除失败'];

    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    public function answers_with_user_info()
    {
        return $this
            ->answers()
            ->with('user')
            ->with('users');
    }
}
