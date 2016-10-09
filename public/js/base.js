;
(function () {
    'use strict';
    angular.module('zhihu', [
        'ui.router',
    ])
        .config(['$interpolateProvider',
            '$stateProvider',
            '$urlRouterProvider',
            function ($interpolateProvider,      //注入
                      $stateProvider,
                      $urlRouterProvider) {
                $interpolateProvider.startSymbol('[:');
                $interpolateProvider.endSymbol(':]');

                $urlRouterProvider.otherwise('/home');

                $stateProvider
                    .state('home', {
                        url: '/home',
                        templateUrl: 'home.tpl' // host/home.tpl
                    })
                    .state('login', {
                        url: '/login',
                        //template: '<h1>登录</h1>',
                        templateUrl: 'login.tpl'
                    })
                    .state('signup', {
                        url: '/signup',
                        //template: '<h1>登录</h1>',
                        templateUrl: 'signup.tpl'
                    })
                    .state('question', {
                        abstract: true,
                        url: '/question',
                        template: '<div ui-view></div>'
                    })
                    .state('question.add', {
                        url: '/add',
                        templateUrl: 'question.add.tpl'
                    })
            }])
        .service('UserService', [
            '$http',
            '$state',
            function ($http, $state) {
                var me = this;
                me.signup_data = {};
                me.login_data = {};
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
        .service('QuestionService', [
            '$http',
            '$state',
            function ($http, $state) {
                var me = this;
                me.new_question = {};
                me.go_add_question = function () {
                    $state.go('question.add');
                }
                me.add = function () {
                    if (!me.new_question.title)
                        return;
                    $http.post('api/question/add', me.new_question)
                        .then(function (r) {
                            if (r.data.status) {
                                me.new_question = {};
                                $state.go('home');
                            }
                        }, function () {
                        })
                }
            }
        ])
        .controller('QuestionAddController', [
            '$scope',
            'QuestionService',
            function ($scope, QuestionService) {
                $scope.Question = QuestionService;
            }])

})();