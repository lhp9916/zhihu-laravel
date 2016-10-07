<!doctype html>
<html lang="zh" ng-app="zhihu">
<head>
    <meta charset="UTF-8">
    <title>知乎</title>
    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <link rel="stylesheet" href="/css/base.css">
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/js/base.js"></script>
</head>
<body>
<div class="navbar clearfix">
    <div class="container">
        <div class="fl">
            <div class="navbar-item brand">知乎</div>
            <div class="navbar-item">
                <input type="text"/>
            </div>
        </div>

        <div class="fr">
            <a class="navbar-item" ui-sref="home">首页</a>
            <a class="navbar-item" ui-sref="login">登录</a>
            <a class="navbar-item" ui-sref="signup">注册</a>
        </div>
    </div>
</div>

<div class="page">
    <div ui-view></div>
</div>
</body>

<script type="text/ng-template" id="home.tpl">
    <div class="home container">
        <h1>首页</h1>
        近一个月因为忙于其他事情，一直没能抽出时间来更新项目进度。现在，只能趁着国庆期间，赶紧抽空更新下进度。这次，我想简单谈谈服务端的一些东西。
        之前，我是没打算将服务端也列入开源名单的。但现在的想法已经改变了，我打算将整个项目都开源，不只是Android端和iOS端，也包括服务端，为一些有志于成为全栈工程师（甚至是全栈架构师）的程序猿提供一个不断进化的完整的学习项目。
    </div>
</script>

<script type="text/ng-template" id="login.tpl">
    <div class="login container">
        <h1>登录</h1>
        近一个月因为忙于其他事情，一直没能抽出时间来更新项目进度。现在，只能趁着国庆期间，赶紧抽空更新下进度。这次，我想简单谈谈服务端的一些东西。
        之前，我
    </div>
</script>
<script type="text/ng-template" id="signup.tpl">
    <div class="signup container" ng-controller="SignupController">
        <div class="card">
            <h1>注册</h1>
            [: User.signup_data :]
            <form name="signup_form" ng-submit="User.signup()">
                <div class="input-group">
                    <label>用户名:</label>
                    <input name="username" type="text"
                           ng-minlength="4"
                           maxlength="16"
                           required
                           ng-model="User.signup_data.username"/>

                    <div class="input-error-set" ng-if="signup_form.username.$touched">
                        <div ng-if="signup_form.username.$error.required">用户名为必填项</div>
                        <div ng-if="signup_form.username.$error.minlength || signup_form.username.$error.maxlength">
                            用户名长度需在4至16位之间
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <label>密码:</label>
                    <input name="password" type="password"
                           ng-minlength="6"
                           required
                           ng-model="User.signup_data.password"/>

                    <div class="input-error-set" ng-if="signup_form.password.$touched">
                        <div ng-if="signup_form.password.$error.required">密码为必填项</div>
                        <div ng-if="signup_form.password.$error.minlength">
                            密码至少为6位
                        </div>
                    </div>
                </div>
                <button type="submit"
                        ng-disabled="signup_form.$invalid">
                    注册
                </button>
            </form>
        </div>
    </div>
</script>
</html>