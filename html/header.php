<!DOCTYPE html>
<html ng-app="myApp">
<head>
	<title>Account</title>
</head>
<link rel="stylesheet" href="html/css/bootstrap.css">
<script src="html/js/jquery.3.2.1.min.js"></script>
 <script src="html/js/angular.min.js"></script> 
<!--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>-->  
<script src="html/js/script.js"></script>
<!-- <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<body>
<div class="container">
<?php if(Session::get('is_login') && Session::get('user_type') == 1){ ?>
<div class="row">
    <div class="col-md-6">Welcome to <?=$user->username?>, Account balance is <?=$user->available_balance?></div>
    <div class="col-md-6"><span class="pull-right"><a href="./logout">Logout</a></span><span class="pull-right"><a href="./">Home</a>&nbsp; | &nbsp; </span> <span class="pull-right"><a href="./fund-transfer">Fund Transfer</a>&nbsp; | &nbsp; </span></div>
  </div>
<?php }else{?>
<div class="row">
    <div class="col-md-6">Welcome to <?=$user->username?></div>
    <div class="col-md-6"><span class="pull-right"><a href="./logout">Logout</a></span><span class="pull-right"><a href="./">Home</a>&nbsp; | &nbsp;</div>
  </div>
<?php }?>