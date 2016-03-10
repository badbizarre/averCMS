'use strict';
 
/* Controllers */

var myApp = angular.module('SimpleApp', ['ngRoute']);

	myApp.controller("demoCtrl", ['$scope','$http','$location', function($scope,$http,$location) {

		$scope.getItems = function() {
			
			$http({
				method: 'POST',			
				url: '/adminpanel/angular/loadng',
			}).then(fulfiled,rejeted);

			function fulfiled(response) {
				console.log('Status: ' + response.status);
				console.log('Type: ' + response.headers("content-type"));

				$scope.items = response.data;
			}
			
			function rejeted(error) {
				console.error(error.status);
				console.error(error.statusText);

			}
			
		}

	}]);
	