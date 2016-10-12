<div class="signup container" ng-controller="SignupController">
    <div class="card">
        <h1>注册</h1>
        {{--[: User.signup_data :]--}}
        <form name="signup_form" ng-submit="User.signup()">
            <div class="input-group">
                <label>用户名</label>
                <input name="username" type="text"
                       ng-minlength="4"
                       maxlength="16"
                       required
                       ng-model-options="{debounce:500}"
                       ng-model="User.signup_data.username"/>

                <div class="input-error-set" ng-if="signup_form.username.$touched">
                    <div ng-if="signup_form.username.$error.required">用户名为必填项</div>
                    <div ng-if="signup_form.username.$error.minlength || signup_form.username.$error.maxlength">
                        用户名长度需在4至16位之间
                    </div>
                    <div ng-if="User.signup_username_exists">
                        用户已存在
                    </div>
                </div>
            </div>
            <div class="input-group">
                <label>密码</label>
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
            <button type="submit" class="primary"
                    ng-disabled="signup_form.$invalid">
                注册
            </button>
        </form>
    </div>
</div>