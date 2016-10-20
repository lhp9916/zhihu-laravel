<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    //时间线api
    public function timeline()
    {
        list($limit, $skip) = pagenate(rq('page'), rq('limit'));
        $questions = get_question_instance()
            ->with('user')
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at', 'desc')
            ->get();

        $answers = get_answer_instance()
            ->with('question')
            ->with('user')
            ->with('users')
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $questions->merge($answers);
        $data = $data->sortByDesc(function ($item) {
            return $item->created_at;
        });
        $data = $data->values()->all();
        return ['status' => 1, 'data' => $data];
    }
}
