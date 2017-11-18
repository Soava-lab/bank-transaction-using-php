<?php
class User{
	public function __construct(){
		$this->user = Http::model('User_model');
	}
	public function signup(){
			$data['msg'] = $this->user->signup();
			Http::html('bheader')->html('signup',$data)->html('footer');
	}
	public function login(){
			$data['msg'] = $this->user->login();
			Http::html('bheader')->html('login',$data)->html('footer');
	}
	public function logout(){
			Session::delete('is_login');
			Request::redirect('./login');
	}
	public function dashboard(){
			if(!$this->user->is_logged()) Request::redirect('./login');
			if(Session::get('user_type') == 1){
				$user['user'] = $this->user->user_info();
				$user['user']->available_balance = Request::label('rs').$user['user']->available_balance;
				Http::html('bheader')->html('dashboard',$user)->html('footer');
			}else{
				$user['user'] = $this->user->user_info();
				Http::html('bheader')->html('admin',$user)->html('footer');
			}
	}
	public function transactions(){
			if(!$this->user->is_logged()) Request::redirect('./login');
			$user['user'] = $this->user->user_info();
			$user['user']->transactions = $this->user->transactions();
			#print_r($user['user']->transactions);
			Http::html('header',$user)->html('transactions',$user)->html('footer');
	}
	public function pending_transactions(){
			if(!$this->user->is_logged()) Request::redirect('./login');
			$user['user'] = $this->user->user_info();
			$user['user']->transactions = $this->user->pending_transactions();
			#print_r($user['user']->transactions);
			Http::html('header',$user)->html('pending_transaction',$user)->html('footer');
	}
	public function all_transactions(){
			if(!$this->user->is_logged()) Request::redirect('./login');
			$user['user'] = $this->user->user_info();
			$user['user']->transactions = $this->user->all_transactions();
			Http::html('header',$user)->html('all_transaction',$user)->html('footer');
	}
	public function fund_transfer(){
			if(!$this->user->is_logged()) Request::redirect('./login');
			$user['user'] = $this->user->user_info();
			$data['user'] = $this->user->account_holders();
			$data['transaction_types'] = $this->user->transaction_types();
			Http::html('header',$user)->html('fund_transfer',$data)->html('footer');
	}
	public function fund_transfer_ajax(){
			if(!$this->user->is_logged()) Http::send(500,"Sorry, Session has expired.");
			$msg = $this->user->fund_transfer();
			if($msg == 'success'){
				Http::send(200,"Fund has been transfer successfuly.");
			}else{
				Http::send(500,"Fund has been transfer successfuly.");
			}
	}
	public function approve_transaction_ajax(){
			if(!$this->user->is_logged()) Http::send(500,"Sorry, Session has expired.");
			$msg = $this->user->approve_transaction();
			if($msg == 'success'){
				Http::send(200,"Transaction has been approved successfuly.");
			}else{
				Http::send(500,'Transaction has been approved successfuly. <a href="./alltransactions">Click here to see all transactions</a>');
			}
	}
	public function export_transaction(){
		 $this->user->export_transaction();		 		
	}
}