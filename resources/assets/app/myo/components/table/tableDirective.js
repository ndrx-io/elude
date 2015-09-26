'use strict';

angular.module('myo')

/**
 *  myo-table directive
 *  
 *  
 *  
 **/

.directive('myoTable', function(appdir) {
    return {
        restrict: 'E',
        scope: {
            headers: '=', //table columns
            rows: '=', //table content from ajax call (array of objects)
            itemsByPage: '=' //number of items by page
        },
        controller: function($scope) {

            console.log('myoTable $scope',$scope);

            //set default items by page
            if (!$scope.itemsByPage) {
                $scope.itemsByPage = 30;
            }

        },
        templateUrl: appdir + '/myo/components/table/table.html'
    };
})
