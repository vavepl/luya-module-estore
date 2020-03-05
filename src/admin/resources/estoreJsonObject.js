zaa.directive("estoreJsonObject", function () {
	return {
		restrict: "E",
		scope: {
			"model": "=",
			"options": "=",
			"label": "@label",
			"i18n": "@i18n",
			"id": "@fieldid",
		},
		controller: ['$scope', function ($scope) {
			console.log($scope.model);

			$scope.helper = [];

			$scope.init = function () {
				if ($scope.model == undefined || $scope.model == null) {
					$scope.model = {};
				} else {
					angular.forEach($scope.model, function (item, index) {
						$scope.helper[index] = false;
					});
				}
			};

			$scope.$watch('model', function (n) {
				if (angular.isArray(n)) {
					$scope.model = {};
				}
				if (n === undefined || n === null) {
					$scope.model = {};
				}
			});

			$scope.add = function (key) {
				if(key == null){
					return;
				}
				$scope.model[key] = '';
			};

			$scope.remove = function (key) {
				delete $scope.model[key];
			};

			$scope.toggleWindow = function (index) {
				if ($scope.helper[index]) {
					$scope.helper[index] = false;
				} else {
					$scope.helper[index] = true;
				}
			};

			$scope.init();
		}],
		templateUrl: 'estoreadmin/set-attribute/json-object'
	}
});