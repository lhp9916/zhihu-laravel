<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function add()
    {
        if (!get_user_instance()->is_logged_in()) {
            return ['status' => 0, 'msg' => '请先登录'];
        }
        if (!rq('question_id') || !rq('content')) {
            return ['status' => 0, 'msg' => 'question_id content 都不能为空'];
        }
        $question = get_question_instance()->find(rq('question_id'));
        if (!$question) {
            return ['status' => 0, 'msg' => '问题不存在'];
        }
//同一个问题，一个人只能回答一次
        $answered = $this
            ->where(['question_id' => rq('question_id'), 'user_id' => session('user_id')])
            ->count();
        if ($answered) {
            return ['status' => 0, 'msg' => '你已经回答过此问题，禁止重复回答'];
        }
        $this->content = rq('content');
        $this->question_id = rq('question_id');
        $this->user_id = session('user_id');
        if ($this->save()) {
            return ['status' => 1, 'id' => $this->id];
        }
        return ['status' => 0, 'msg' => '数据库插入失败'];
    }

    public function change()
    {
        if (!get_user_instance()->is_logged_in()) {
            return ['status' => 0, 'msg' => '请先登录'];
        }
        $id = rq('id');
        $content = rq('content');
        if (!$id || !$content) {
            return ['status' => 0, 'msg' => 'id 和 content 都不能为空'];
        }
        $answer = $this->find($id);//返回主键所在行
        if (!$answer) {
            return ['status' => 0, 'msg' => '回答不存在'];
        }
        if ($answer->user_id != session('user_id')) {
            return ['status' => 0, 'msg' => '您没有权限修改'];
        }
        $answer->content = $content;
        if ($answer->save()) {
            return ['status' => 1, 'msg' => '修改成功'];
        }
        return ['status' => 0, 'msg' => '数据库插入失败'];
    }
}
