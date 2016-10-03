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

function get_question_instance()
{
    return new App\Question();
}

Route::get('/', function () {
    return view('welcome');
});
//测试入口
Route::any('test', function () {
//    dd(get_user_instance()->is_logged_in());
});

Route::any('api', function () {
    return ['version' => 0.1];
});
Route::any('api/signup', function () {
    return get_user_instance()->signup();
});
Route::any('api/login', function () {
    return get_user_instance()->login();
});
Route::any('api/logout', function () {
    return get_user_instance()->logout();
});
Route::any('api/question/add', function () {
    return get_question_instance()->add();
});