<!doctype html>
<html lang="zh" ng-app="zhihu" user-id="{{session('user_id')}}">
<head>
    <meta charset="UTF-8">
    <title>知乎</title>
    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <link rel="stylesheet" href="/css/base.css">
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/js/base.js"></script>
    <script src="/js/common.js"></script>
    <script src="/js/question.js"></script>
    <script src="/js/user.js"></script>
    <script src="/js/answer.js"></script>
</head>
<body>
<div class="navbar clearfix">
    <div class="container">
        <div class="fl">
            <form ng-submit="Question.go_add_question()" id="quick_ask" ng-controller="QuestionAddController">
                <a href="/">
                    <div class="navbar-item brand">知乎</div>
                </a>

                <div class="navbar-item">
                    <input type="text" ng-model="Question.new_question.title"/>
                </div>
                <div class="navbar-item">
                    <button type="submit">提问</button>
                </div>
            </form>
        </div>

        <div class="fr">
            <a class="navbar-item" ui-sref="home">首页</a>
            @if(is_logged_in())
                <a class="navbar-item" href="{{ url('/api/logout') }}">登出</a>
                <a class="navbar-item">{{session('username')}}</a>
            @else
                <a class="navbar-item" ui-sref="login">登录</a>
                <a class="navbar-item" ui-sref="signup">注册</a>
            @endif
        </div>
    </div>
</div>

<div class="page">
    <div ui-view></div>
</div>
</body>
</html>