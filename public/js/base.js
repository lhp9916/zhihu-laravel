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
            }])
        .service('UserService', [function () {
            var me = this;
            me.signup_data = {};
            me.signup = function () {

            }
        }])

        .controller('SignupController', [
            '$scope',
            'UserService',
            function ($scope, UserService) {
                $scope.User = UserService;
            }])

})();