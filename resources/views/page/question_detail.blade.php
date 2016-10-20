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
        <div class="answer-block">
            <di ng-repeat="item in Question.current_question.answers_with_user_info">
                <div>
                    <span ui-sref="user({id:item.user.id})">
                        [: item.user.username :]
                    </span>
                </div>
                <div>[: item.content :]</div>
                <div class="hr"></div>
            </di>
        </div>
    </div>
</div>