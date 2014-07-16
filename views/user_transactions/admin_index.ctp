<div class="container" ng-app="activity-report" ng-controller="ActivityController">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 bleach no-pad">

			<nav class="navbar navbar-default navbar-static-top" style="margin:0px;padding-left:5px;padding-right:5px;border-bottom:none">
				<div class="container-fluid">
					<button class="btn btn-default navbar-btn">
						Docs
					</button>
					<button class="btn btn-default navbar-btn" ng-click="sendToActivityReport()">
						Activity Report
					</button>

					<ul class="nav navbar-nav navbar-right" style="margin-right:-5px">
						<li>
							<a href ng-click="previousPage()" disabled>
								<i class="fa fa-angle-left"></i>
							</a>
						</li>
						<li>
							<a href>
								{{ (offset/limit) + 1 }}
							</a>
						</li>
						<li>
							<a href ng-click="nextPage()" disabled>
								<i class="fa fa-angle-right"></i>
							</a>
						</li>
					</ul>
				</div>
			</nav>

			<nav class="navbar navbar-default" style="border-radius:0px;border-left:none;border-right:none">
				<div class="container-fluid">
					<form class="navbar-form" style="display:inline-block">
						From Date
						<div class="form-group" style="margin-right:10px;">
							<input type="text" class="form-control input-sm dater" ng-model="from" />
						</div>

						To Date
						<div class="form-group">
							<input type="text" class="form-control input-sm dater" ng-model="to" />
						</div>
					</form>

					<form class="navbar-form navbar-right" style="display:inline-block;margin-right:-5px">
						Set Module
						<div class="form-group">
							<select ng-model="smodule" class="form-control" style="width:150px">
								<option></option>
								<option ng-repeat="module in modules">
									{{ module }}
								</option>
							</select>
						</div>
					</form>
				</div>
			</nav>

		</div>
	</div>
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 bleach no-pad">

			<table class="table table-striped table-bordered" style="margin-bottom:0px;bor">
				<tr>
					<th style="width:49%">
						<a href>Details</a>
					</th>
					<th style="width:10%">
						<a href ng-click="setOrder('location')">Location</a>
						<i class="fa fa-angle-up" ng-if="asc && order == 'location'"></i>
						<i class="fa fa-angle-down" ng-if="!asc && order == 'location'"></i>
					</th>
					<th style="width:20%">
						<a href ng-click="setOrder('module')">Module</a>
						<i class="fa fa-angle-up" ng-if="asc && order == 'module'"></i>
						<i class="fa fa-angle-down" ng-if="!asc && order == 'module'"></i>
					</th>
					<th>
						<a href ng-click="setOrder('created')">Ocurred</a>
						<i class="fa fa-angle-up" ng-if="asc && order == 'created'"></i>
						<i class="fa fa-angle-down" ng-if="!asc && order == 'created'"></i>
					</th>
				</tr>
			</table>

		</div>
	</div>
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 bleach no-pad" style="height:350px;overflow-y:scroll">

			<table class="table table-striped" >
				<tr ng-repeat="user_transaction in filtered_transactions.slice(offset, offset+limit)">
					<td style="width:50%">
						{{ user_transaction.details }}
					</td>
					<td style="width:10%">
						{{ user_transaction.location }}
					</td>
					<td style="width:20%">
						{{ user_transaction.module }}
					</td>
					<td>
						{{ user_transaction.created | todate | date:'short' }}
					</td>
				</tr>
			</table>

		</div>
	</div>
</div>

<iframe style="display:none" id="_blank"></iframe>

<script>
var user_id = '<?= $this->params['pass'][0] ?>';
var app = angular.module('activity-report', ['infinite-scroll']);

$('.dater').datepicker();

app.controller('ActivityController', function($scope, $http){
	$scope.from 	= '';
	$scope.to 		= '';
	$scope.offset 	= 0;
	$scope.limit 	= 20;
	$scope.asc 		= false;
	$scope.order 	= 'created';
	$scope.smodule 	= '';
	$scope.all_transactions = [];
	$scope.filtered_transactions = [];

	$scope.passesDate = function(transaction) {
		var to = new Date($scope.to);
		var from = new Date($scope.from);
		var created	= new Date(transaction.created);

		if($scope.from != '' && $scope.to != '')
		{
			return (from <= created && to >= created);
		}
		else if($scope.from != '')
		{
			return from <= created;
		}
		else if($scope.to != '')
		{
			return to >= created;
		}

		return true;
	};

	$scope.passesModule = function(transaction) {
		if($scope.smodule != '')
			return (transaction.module == $.trim($scope.smodule));
		else
			return true;
	};

	$scope.nextPage = function() {
		var newOffset = $scope.offset + $scope.limit;

		if(newOffset <= $scope.all_transactions.length)
		{
			$scope.offset = newOffset;
		}
	};

	$scope.previousPage = function() {
		var newOffset = $scope.offset - $scope.limit;
		if(newOffset <= 0)
		{
			$scope.offset = 0;
		}
		else
		{
			$scope.offset = newOffset;
		}
	};

	$scope.setOrder = function(order) {
		if($scope.order == order)
		{
			$scope.asc = !$scope.asc;
			$scope.filtered_transactions.reverse();
		}
		else
		{
			$scope.filtered_transactions = _.sortBy($scope.filtered_transactions, function(trans){
				return trans[order];
			});
		}
		$scope.order = order;
	};

	$scope.filter = function() {
		$scope.filtered_transactions = [];
		for(var i = 0; i < $scope.all_transactions.length; i+=1)
		{
			var transaction = $scope.all_transactions[i];
			if($scope.passesModule(transaction) && $scope.passesDate(transaction))
				$scope.filtered_transactions.push(transaction);
		}
	};

	$scope.sendToActivityReport = function() {
		var url = '/admin/user_transactions/report/' + user_id + '?';

		if($scope.from != '')
			url += 'from=' + $scope.from;

		if($scope.to != '')
			url += '&to=' + $scope.to;

		if($scope.smodule != '')
			url += '&smodule=' + $scope.smodule;

		url += '&asc=' + $scope.asc;
		url += '&order=' + $scope.order;

		window.location.href = url;
	};

	$http.post('/admin/user_transactions/index/' + user_id)
	.success(function(resp){
		$scope.all_transactions = resp.output;
		$scope.filter();
	});

	$http.get('/admin/user_transactions/modules')
	.success(function(resp){
		$scope.modules = resp.output;
	});

	$scope.$watch('smodule', $scope.filter);
	$scope.$watch('from', $scope.filter);
	$scope.$watch('to', $scope.filter);
});

app.filter('todate', function(){
	return function(input){
		var date = new Date(input);
		return date;
	};
});
</script>









