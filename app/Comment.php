<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function add()
    {
        if (!get_user_instance()->is_logged_in()) {
            return ['status' => 0, 'msg' => '请先登录'];
        }
        $content = rq('content');
        if (!$content) {
            return ['status' => 0, 'msg' => '评论内容为空'];
        }
        $this->content = $content;
        $answer_id = rq('answer_id');
        $question_id = rq('question_id');
        if (
            (!$answer_id && !$question_id) || //都为null
            ($question_id && $answer_id)    //都存在
        ) {
            return ['status' => 0, 'msg' => 'answer_id或者question_id不能为空'];
        }
        //给问题评论
        if ($question_id) {
            $question = get_question_instance()->find($question_id);
            if (!$question) {
                return ['status' => 0, 'msg' => '问题不存在'];
            }
            $this->question_id = $question_id;
        }
        //给回答评论
        if ($answer_id) {
            $answer = get_answer_instance()->find($answer_id);
            if (!$answer) {
                return ['status' => 0, 'msg' => '回答不存在'];
            }
            $this->answer_id = $answer_id;
        }

        $replay_to = rq('replay_to');
        if ($replay_to) {
            $target = $this->find($replay_to);
            if (!$target) {
                return ['status' => 0, 'msg' => 'target comment 不存在'];
            }
            if ($target->user_id == session('user_id')) {//不允许评论自己的
                return ['status' => 0, 'msg' => '不能评论你自己的问题'];
            }
            $this->replay_to = $replay_to;
        }

        $this->user_id = session('user_id');
        if ($this->save()) {
            return ['status' => 1, 'id' => $this->id];
        }
        return ['status' => 0, 'msg' => '保存失败'];
    }

    public function read()
    {
        $question_id = rq('question_id');
        $answer_id = rq('answer_id');
        if (!$question_id && !$answer_id) {
            return ['status' => 0, 'msg' => 'answer_id或者question_id不能为空'];
        }
        if ($question_id) {
            $question = get_question_instance()->find($question_id);
            if (!$question) {
                return ['status' => 0, 'msg' => '问题不存在'];
            }
            $data = $this->where('question_id', $question_id)->get()->keyBy('id');
        } else {
            $answer = get_answer_instance()->find($answer_id);
            if (!$answer) {
                return ['status' => 0, 'msg' => '回答不存在'];
            }
            $data = $this->where('answer_id', $answer_id)->get()->keyBy('id');
        }
        return ['status' => 1, 'data' => $data];
    }

    public function remove()
    {
        if (!get_user_instance()->is_logged_in()) {
            return ['status' => 0, 'msg' => '请先登录'];
        }
        $id = rq('id');
        if (!$id) {
            return ['status' => 0, 'id' => 'id不能为空'];
        }
        $comment = $this->find($id);
        if (!$comment) {
            return ['status' => 0, 'msg' => '评论不存在'];
        }
        if ($comment->user_id != session('user_id')) {
            return ['status' => 0, 'msg' => '权限错误'];
        }
        //先删除此评论下的所有回复，再删除评论
        $this->where('replay_to', $comment->id)->delete();

        if ($comment->delete()) {
            return ['status' => 1, 'msg' => '删除成功'];
        }
        return ['status' => 0, 'msg' => '数据库写入失败'];
    }
}
