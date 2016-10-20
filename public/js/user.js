;
(function () {
    'use strict';
    angular.module('user', [
        'answer',
    ])

        .service('UserService', [
            '$http',
            '$state',
            function ($http, $state) {
                var me = this;
                me.signup_data = {};
                me.login_data = {};
                me.data = {};
                me.signup = function () {
                    $http.post('api/signup', me.signup_data)
                        .then(function (rs) {
                            if (rs.data.status) {
                                me.signup_data = {};
                                $state.go('login');
                            }
                        }, function (e) {
                            console.log('e', e);
                        })
                }
                me.username_exits = function () {
                    $http.post('api/user/exists', {username: me.signup_data.username})
                        .then(function (rs) {
                            if (rs.data.status && rs.data.data.count) {
                                me.signup_username_exists = true;
                            } else {
                                me.signup_username_exists = false;
                            }
                        }, function (e) {
                            console.log('e', e);
                        })
                }
                me.login = function () {
                    $http.post('/api/login', me.login_data)
                        .then(function (rs) {
                            if (rs.data.status) {
                                location.href = '/';
                            } else {
                                me.login_failed = true;
                            }
                        }, function (e) {

                        })
                }
                me.read = function (param) {
                    return $http.post('/api/user/read', param)
                        .then(function (r) {
                            if (r.data.status) {
                                me.current_user = r.data.data;
                                me.data[param.id] = r.data.data;
                            } else {
                                if (r.data.msg == "请先登陆") {
                                    $state.go('login');
                                }
                            }
                        }, function () {

                        })
                }
            }])

        .controller('SignupController', [
            '$scope',
            'UserService',
            function ($scope, UserService) {
                $scope.User = UserService;
                $scope.$watch(function () {
                    return UserService.signup_data;
                }, function (n, o) {
                    if (n.username != o.username) {
                        UserService.username_exits();
                    }
                }, true);

            }])

        .controller('LoginController', [
            'UserService',
            '$scope',
            function (UserService, $scope) {
                $scope.User = UserService;
            }
        ])

        .controller('UserController', [
            'UserService',
            '$scope',
            '$stateParams',
            'AnswerService',
            'QuestionService',
            function (UserService, $scope, $stateParams, AnswerService, QuestionService) {
                $scope.User = UserService;
                UserService.read($stateParams);
                AnswerService.read({user_id: $stateParams.id})
                    .then(function (r) {
                        if (r) {
                            UserService.his_answers = r;
                        }
                    });
                QuestionService.read({user_id: $stateParams.id})
                    .then(function (r) {
                        if (r) {
                            UserService.his_questions = r;
                        }
                    });
            }
        ])

})();