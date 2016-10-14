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
                me.cunrent_page = 1;
                me.get = function (conf) {
                    if (me.pending) return;
                    me.pending = true;

                    conf = conf || {page: me.cunrent_page};

                    $http.post('/api/timeline', conf)
                        .then(function (r) {
                            if (r.data.status) {
                                if (r.data.data.length) {
                                    me.data = me.data.concat(r.data.data);
                                    me.data = AnswerService.count_vote(me.data);
                                    me.cunrent_page++;
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

                me.vote = function (conf) {
                    AnswerService.vote(conf)
                        .then(function (r) {
                            if (r) {
                                AnswerService.update_data(conf.id);
                            }
                        })
                }
            }
        ])

        .controller('HomeController', [
            '$scope',
            'TimelineServices',
            'AnswerService',
            function ($scope, TimelineServices, AnswerService) {
                $scope.Timeline = TimelineServices;
                TimelineServices.get();
                var $win = $(window);
                $win.on('scroll', function () {
                    if ($win.scrollTop() - ($(document).height() - $win.height()) > -30) {
                        TimelineServices.get();
                    }
                })

                $scope.$watch(function () {
                    return AnswerService.data;
                }, function (new_data, old_data) {
                    var timeline_data = TimelineServices.data;
                    //比对新旧数据
                    for (var k in new_data) {
                        for (var i = 0; i < timeline_data.length; i++) {
                            if (k == timeline_data[i].id) {
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