;
(function () {
    'use strict';
    angular.module('common', [])

        .service('TimelineServices', [
            '$http',
            'AnswerService',
            function ($http, AnswerService) {
                var me = this;
                me.data = [];
                me.current_page = 1;
                me.no_more_data = false;
                //获取首页数据
                me.get = function (conf) {
                    if (me.pending || me.no_more_data) return;
                    me.pending = true;

                    conf = conf || {page: me.current_page};
                    //统计票数
                    $http.post('/api/timeline', conf)
                        .then(function (r) {
                            if (r.data.status) {
                                if (r.data.data.length) {
                                    me.data = me.data.concat(r.data.data);
                                    me.data = AnswerService.count_vote(me.data);
                                    me.current_page++;
                                } else {
                                    me.no_more_data = true;
                                }
                            } else {
                                console.error('网络错误');
                            }
                        }, function () {
                            console.error('网络错误');
                        })
                        .finally(function () {
                            me.pending = false;
                        })
                }
                //在时间线中投票
                me.vote = function (conf) {
                    //调用核心投票功能
                    AnswerService.vote(conf)
                        .then(function (r) {
                            //如果投票成功就更新AnswerService中的数据
                            if (r) {
                                AnswerService.update_data(conf.id);
                            }
                        })
                }
                me.reset_state = function () {
                    me.data = [];
                    me.current_page = 1;
                    me.no_more_data = 0;
                }
            }
        ])

        .controller('HomeController', [
            '$scope',
            'TimelineServices',
            'AnswerService',
            function ($scope, TimelineServices, AnswerService) {
                $scope.Timeline = TimelineServices;
                TimelineServices.reset_state();
                TimelineServices.get();
                var $win = $(window);
                $win.on('scroll', function () {
                    if ($win.scrollTop() - ($(document).height() - $win.height()) > -30) {
                        TimelineServices.get();
                    }
                })

                //监控回答数据的变化
                $scope.$watch(function () {
                    return AnswerService.data;
                }, function (new_data, old_data) {
                    var timeline_data = TimelineServices.data;
                    //比对新旧数据
                    for (var k in new_data) {
                        for (var i = 0; i < timeline_data.length; i++) {
                            if (k == timeline_data[i].id) {
                                //更新时间线中的回答数据
                                timeline_data[i] = new_data[k];
                            }
                        }
                    }
                    //重新统计票数
                    TimelineServices.data = AnswerService.count_vote(TimelineServices.data);
                }, true)
            }
        ])
})();