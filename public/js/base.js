;
(function () {
    'use strict';
    angular.module('zhihu', [])
        .config(function ($interpolateProvider) {
            $interpolateProvider.startSymbol('[:');
            $interpolateProvider.endSymbol(':]');
        })
})();