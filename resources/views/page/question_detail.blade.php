<div class="container question-detail" ng-controller="QuestionDetailController">
    <div class="card">
        <h1>[: Question.current_question.title :]</h1>

        <div class="desc">
            [: Question.current_question.desc :]
        </div>
        <div>
            <span class="gray">
                回答数：[: Question.current_question.answers.length :]
            </span>
        </div>
        <div class="hr"></div>
        <div class="feed item clearfix">
            <div
                    ng-if="!Question.current_answer_id || Question.current_answer_id == item.id"
                    ng-repeat="item in Question.current_question.answers_with_user_info">
                <div class="vote">
                    <div ng-click="Question.vote({id:item.id,vote:1})" class="up">
                        赞[: item.upvote_count :]
                    </div>
                    <div ng-click="Question.vote({id:item.id,vote:2})" class="down">
                        踩[: item.downvote_count :]
                    </div>
                </div>
                <div class="feed-item-content">
                    <div>
                    <span ui-sref="user({id:item.user.id})">
                        [: item.user.username :]
                    </span>
                    </div>
                    <div>
                        [: item.content :]
                        <div class="gray">
                            <a ui-sref="question.detail({id:Question.current_question.id,answer_id:item_id})">
                                [: item.updated_at :]
                            </a>
                        </div>
                    </div>
                </div>
                <div class="hr"></div>
            </div>
        </div>
    </div>
</div>