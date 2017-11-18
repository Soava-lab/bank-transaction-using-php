<?php
error_reporting(E_ALL);
ini_set("display_errors", -1);
setlocale(LC_ALL, 'en_US.UTF8');
#==============================================#
define("SHA",false); # Small Http::SH Authentication [ Don't remove it ]
#==============================================#
# Notes : if you create a project via composer it will help to access third party.
require 'vendor/autoload.php';
require 'autoload.php';


$year = date("Y");
#echo date("z", mktime(0,0,0,12,31,$year)) + 1;

$app = new Http();

$app->get('/',function($app){
	if(Session::get('is_login')){
    	Request::location('dashboard');
	}else{
		$app->html('bheader')->html('home')->html('footer');
	}
});

$app->page('/signup','User::signup');

$app->page('/login','User::login');

$app->get('/dashboard','User::dashboard');

if(Session::get('is_login') && Session::get('user_type') == 1){ # Customer 
	
	$app->get('/transactions','User::transactions');
	
	$app->get('/fund-transfer','User::fund_transfer');
	
	$app->post('/fund-transfer','User::fund_transfer_ajax');
	
	$app->get('/export-transaction','User::export_transaction');

}else if(Session::get('is_login') && Session::get('user_type') == 2){ # Administrator 
	$app->get('/transactions','User::pending_transactions');
	$app->get('/alltransactions','User::all_transactions');
	$app->post('/approve-transaction','User::approve_transaction_ajax');
}

$app->get('/logout','User::logout');

$app->run(); # Extender
