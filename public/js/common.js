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
            }
        ])

        .controller('HomeController', [
            '$scope', 'TimelineServices',
            function ($scope, TimelineServices) {
                $scope.Timeline = TimelineServices;
                TimelineServices.get();
                var $win = $(window);
                $win.on('scroll', function () {
                    if ($win.scrollTop() - ($(document).height() - $win.height()) > -30) {
                        TimelineServices.get();
                    }
                })
            }
        ])
})();