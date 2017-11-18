var app = angular.module('myApp', []);

app.controller("fund_transfer",function($scope,$http,$timeout){
	
	$scope.fund_transfer_submit = function(isValid){
		//$scope.amount = 'John Doe';
		if (isValid) {
			var dataObj = {
					amount : $scope.amount,
					account_id : $scope.account_id,
					txt_type_id : $scope.txt_type_id,
					note : $scope.note
			};
			//console.log(dataObj);
		//var data = 'amount='+$scope.amount+'&account_id='+$scope.account_id+'&txt_type_id='+$scope.txt_type_id+'&note='+$scope.note;
	       $http.post("./fund-transfer",JSON.stringify(dataObj)).then(
		       function(response){ console.log(response);
		         // success callback
		         if(response!="" && response.data!=""){
		         	if(response.data.status){
		         		$scope.transfer_msg = response.data.body;
		         		$scope.transfer_msg_class = 'alert-success';
		         		$timeout(function() {
					      document.location.href = './transactions';
					      }, 
					    3000);
		         	}
		         }
		       }, 
		       function(response){
		         // failure callback
		         $scope.transfer_msg_class = 'alert-danger';
		       }
		    );
	    }else{
	    	$scope.invalid = true;
	    }
	    
	 }
 
  $scope.approve_transaction = function(ref_id){
	  
	  if(ref_id!= ""){
			  var dataObj = {
							ref_id : ref_id
					};
			  $http.post("./approve-transaction",JSON.stringify(dataObj)).then(
				   function(response){ console.log(response);
					 // success callback
					 if(response!="" && response.data!=""){
						if(response.data.status){
							$scope.transfer_msg = response.data.body;
							$scope.transfer_msg_class = 'alert-success';
							$(".ref_"+ref_id).hide();
						}
					 }
				   }, 
				   function(response){
					 // failure callback
					 $scope.transfer_msg_class = 'alert-danger';
				   }
				);
	   }
   }
});
