zaa.directive('estoreJsonObjectArray', function () {
	return {
		restrict: 'E',
		scope: {
			'model': '=',
			'options': '=',
			'label': '@label',
			'i18n': '@i18n',
			'id': '@fieldid',
		},
		controller: ['$scope', '$element', '$timeout', function ($scope, $element, $timeout) {
			console.log(typeof $scope.model);

			$scope.init = function () {
				if ($scope.model === undefined || $scope.model === null) {
					$scope.add();
				} else if (typeof $scope.model == 'string' && $scope.model.length > 0) {
					$scope.model = JSON.parse($scope.model);
				} else {
					$scope.add();
				}
			};

			$scope.add = function () {
				if ($scope.model == null || $scope.model === '' || $scope.model === undefined) {
					$scope.model = [];
				}
				$scope.model.push({});
				$scope.setFocus();
			};

			$scope.remove = function (key) {
				if($scope.model.length > 1){
					$scope.model.splice(key, 1);
				}
			};

			$scope.refactor = function (key, row) {
				if (key !== ($scope.model.length - 1)) {
					if (row.value === '') {
						$scope.remove(key);
					}
				}
			};

			$scope.setFocus = function () {
				$timeout(function () {
					var input = $element.children('.list').children('.list__item:last-of-type').children('.list__left').children('input');

					if (input.length === 1) {
						input[0].focus();
					}
				}, 50);
			};

			$scope.moveUp = function (index) {
				index = parseInt(index);
				var oldRow = $scope.model[index];
				$scope.model[index] = $scope.model[index - 1];
				$scope.model[index - 1] = oldRow;
			};

			$scope.moveDown = function (index) {
				index = parseInt(index);
				var oldRow = $scope.model[index];
				$scope.model[index] = $scope.model[index + 1];
				$scope.model[index + 1] = oldRow;
			};

			$scope.showDownButton = function (index) {
				if (parseInt(index) < Object.keys($scope.model).length - 1) {
					return true;
				}
				return false;
			};

			$scope.init();

		}],
		templateUrl: 'estoreadmin/set-attribute/json-object-array'
	}
});
