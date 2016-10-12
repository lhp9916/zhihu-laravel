<div ng-controller="HomeController" class="home container card">
    <h1>最近动态</h1>

    <div class="hr"></div>
    <div class="item-set">
        <div ng-repeat="item in Timeline.data" class="item">
            {{--点赞--}}
            <div class="vote"></div>
            <div class="feed-item-content">
                <div ng-if="item.question_id" class="content-act">[: item.user.username :]添加了回答</div>
                <div ng-if="!item.question_id" class="content-act"> [: item.user.username :]添加了提问</div>
                <div class="title">[: item.title :]</div>
                <div class="content-owner"> [: item.user.username :]
                    <span class="desc">descdescdesc</span>
                </div>
                <div class="content-main">
                    [: item.desc :]
                </div>
                <div class="action-set">
                    <div class="comment">评论</div>
                </div>
                <div class="comment-block">
                    <div class="hr"></div>
                    <div class="comment-item-set">
                        <div class="rect"></div>
                        <div class="comment-item clearfix">
                            <div class="user">liming</div>
                            <div class="comment-content">
                                只能怪自己手贱升级了iOS10，于是微信聊天时候经常会出现我跟一个人斗完图之后，再次进入微信跟其他人继续聊天时，屏幕上出现的是九宫格样子，但其实手机还停留在我跟上一个人发表情的页面！！！
                                于是不知不觉发过去了只能怪自己手贱升级了iOS10，于是微信聊天时候经常会出现我跟一个人斗完图之后，再次进入微信跟其他人继续聊天时，屏幕上出现的是九宫格样子，但其实手机还停留在我跟上一个人发表情的页面！！！
                                于是不知不觉发过去了
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hr"></div>
        </div>
        <div ng-if="Timeline.pending" class="tac">没有更多数据啦</div>
        <div ng-if="Timeline.no_more_data" class="tac">没有更多数据啦</div>
    </div>
</div>