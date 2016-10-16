<div ng-controller="UserController">
    <div class="user card container">
        <h1>用户详情</h1>

        <div class="hr"></div>
        <div class="basic">
            <div class="info_item clearfix">
                <div>用户名</div>
                <div>[: User.self_data.username :]</div>
            </div>
            <div class="info_item clearfix">
                <div>简介</div>
                <div>[: User.self_data.intro || '暂无介绍' :]</div>
            </div>
        </div>
        <h2>用户提问</h2>

        <div ng-repeat="(key,value) in User.his_questions">
            [: value.title :]
        </div>

        <h2>用户回答</h2>

        <div ng-repeat="(key,value) in User.his_answers">
            [: value.content :]
        </div>
    </div>
</div>