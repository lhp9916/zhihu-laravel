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
        if($this->save()){
            return ['status' => 1, 'id' => $this->id];
        }
        return ['status' => 0, 'msg' => '保存失败'];
    }
}
