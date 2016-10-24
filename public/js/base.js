;
(function () {
    'use strict';
    //保存用户的Id
    window.his = {
        id: parseInt($('html').attr('user-id'))
    };

    angular.module('zhihu', [
        'ui.router',
        'common', //加载模块
        'question',
        'answer',
        'user',
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
                        templateUrl: 'tpl/page/home' // host/home.tpl
                    })
                    .state('login', {
                        url: '/login',
                        templateUrl: 'tpl/page/login'
                    })
                    .state('signup', {
                        url: '/signup',
                        templateUrl: 'tpl/page/signup'
                    })
                    .state('question', {
                        abstract: true,
                        url: '/question',
                        template: '<div ui-view></div>',
                        controller: 'QuestionController',
                    })
                    .state('question.detail', {
                        url: '/detail/:id?answer_id',
                        templateUrl: 'tpl/page/question_detail'
                    })
                    .state('question.add', {
                        url: '/add',
                        templateUrl: 'tpl/page/question_add'
                    })
                    .state('user', {
                        url: '/user/:id',
                        templateUrl: 'tpl/page/user'
                    })
            }])

})();