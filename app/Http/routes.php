<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

function pagenate($page = 1, $limit = 16)
{
    $limit = $limit ?: 16;
    $skip = ($page ? $page - 1 : 0) * $limit;
    return [$limit, $skip];
}

function error($msg = null)
{
    return ['status' => 0, 'msg' => $msg];
}

function success($data_to_add = [])
{
    $data = ['status' => 1, 'data' => []];
    if ($data_to_add) {
        $data['data'] = $data_to_add;
    }
    return $data;
}

//简化Request取值
function rq($key = null, $default = null)
{
    if (!$key) {
        return Request::all();
    } else {
        return Request::get($key, $default);
    }
}

function get_user_instance()
{
    return new App\User();
}

function is_logged_in()
{
    return session('user_id') ?: false;
}

function get_question_instance()
{
    return new App\Question();
}

function get_answer_instance()
{
    return new App\Answer();
}

function get_comment_instance()
{
    return new App\Comment();
}

Route::get('/', function () {
    return view('index');
});
//测试入口
Route::any('test', function () {
//    dd(get_user_instance()->is_logged_in());
});

Route::any('api', function () {
    return ['version' => 0.1];
});
//--------------用户API-------------------------------
Route::any('api/signup', function () {
    return get_user_instance()->signup();
});
Route::any('api/login', function () {
    return get_user_instance()->login();
});
Route::any('api/logout', function () {
    return get_user_instance()->logout();
});
Route::any('api/user/read', function () {
    return get_user_instance()->read();
});
Route::any('api/user/change_password', function () {
    return get_user_instance()->change_password();
});
Route::any('api/user/reset_password', function () {
    return get_user_instance()->reset_password();
});
Route::any('api/user/validate_reset_password', function () {
    return get_user_instance()->validate_reset_password();
});
Route::any('api/user/exists', function () {
    return get_user_instance()->exists();
});
Route::any('api/user/is_logged_in', function () {
    return get_user_instance()->is_logged_in();
});

//--------------问题API-------------------------------
Route::any('api/question/add', function () {
    return get_question_instance()->add();
});
Route::any('api/question/change', function () {
    return get_question_instance()->change();
});
Route::any('api/question/read', function () {
    return get_question_instance()->read();
});
Route::any('api/question/remove', function () {
    return get_question_instance()->remove();
});

//--------------回答API-------------------------------
Route::any('api/answer/add', function () {
    return get_answer_instance()->add();
});
Route::any('api/answer/change', function () {
    return get_answer_instance()->change();
});
Route::any('api/answer/read', function () {
    return get_answer_instance()->read();
});
Route::any('api/answer/vote', function () {
    return get_answer_instance()->vote();
});

//--------------评论API---------------------------------------
Route::any('api/comment/add', function () {
    return get_comment_instance()->add();
});
Route::any('api/comment/read', function () {
    return get_comment_instance()->read();
});
Route::any('api/comment/remove', function () {
    return get_comment_instance()->remove();
});

//时间线API
Route::any('api/timeline', 'CommonController@timeline');

Route::get('tpl/page/home', function () {
    return view('page.home');
});
Route::get('tpl/page/signup', function () {
    return view('page.signup');
});
Route::get('tpl/page/login', function () {
    return view('page.login');
});
Route::get('tpl/page/question_add', function () {
    return view('page.question_add');
});
Route::get('tpl/page/user', function () {
    return view('page.user');
});
Route::get('tpl/page/question_detail', function () {
    return view('page.question_detail');
});