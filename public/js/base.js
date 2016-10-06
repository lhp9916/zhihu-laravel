;
(function () {
    'use strict';
    angular.module('zhihu', [
        'ui.router',
    ])
        .config(function ($interpolateProvider,      //注入
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
        })
})();