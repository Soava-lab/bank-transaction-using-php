<?php
class User_model{
	public function signup(){ if(self::is_logged()) Request::location('./dashboard');

		 $msg = '';
		 if(Request::is_post('doSubmit')){  $app = new Http();
			 if(Request::post('email')!='' && Request::post('password')!='' && Request::post('username')!=''){
			 	Request::unset_post('doSubmit');
			 	$_POST['password'] = md5(Request::post('password'));
				#$app->db->get_where("users",array("email"=>Request::post('email')));
				$app->db->where("(username='".Request::post('username')."' OR email='".Request::post('email')."')");
				$query = $app->db->get('users');
				if($query->num_rows() == 0){
					$app->db->insert("users",Request::post());
					if($app->db->insert_id()){
						$app->db->query("INSERT INTO account (user_id,available_balance) VALUES (".$app->db->insert_id().",0)");
						$msg = '<span class="text-success">Registration has been done.</span>';
					}
				}else{
					$msg = '<span class="text-danger">Sorry, Username or Email already exist.</span>';
				}
			 	
			 }else{
			 		$msg = '<span class="text-danger">Please fill out required fields.</span>';
			 	}
		}
		return $msg;
	}
	public function login(){ if(self::is_logged()) Request::location('./dashboard');

		 	$msg = '';
		 	if(Request::is_post('username,password')){
				if(Request::post('username')!='' && Request::post('password')!=''){
					$query = db()->get_where("users",array("username"=>Request::post('username'),"password"=>md5(Request::post('password'))));
					if($query->num_rows() > 0){						
						$fet = $query->row();
						if($fet->user_type == 1){
						$query1 = db()->get_where("account",array("user_id"=>$fet->user_id));
						$fetch = $query1->row();
						Session::set("account_id",$fetch->account_id);
						}
						
						Session::set("is_login",true);
						Session::set("user_id",$fet->user_id);
						Session::set("user_type",$fet->user_type);
						Request::location('./dashboard');
						die;
					}else{
						$msg = "Invalid login credential.";
					}
				}else{
						$msg = 'Please fill out required fields.';
				}
			}
		return $msg;
	}
	public function is_logged(){
		if(Session::get('is_login')){
			return true;
		}else{
			return false;
		}
	}
	public function user_info(){ $user = (Object)[];
		if(Session::get('is_login')){
			$user = db()->query("SELECT * FROM users u LEFT JOIN account a ON u.user_id=a.user_id WHERE u.user_id=".Session::get('user_id')." ORDER BY a.account_id DESC limit 1")->row();
		}
		return $user;
	}
	public function transactions(){ $user = (Object)[];
		if(Session::get('is_login')){
						
			$trans = db()->query("SELECT u.username,t.*,(select us.username from users us INNER JOIN account ac ON ac.user_id=us.user_id where ac.account_id= r.to_account_id limit 1) as benificiary FROM users u INNER JOIN account a ON u.user_id=a.user_id LEFT JOIN reference r ON a.account_id = r.from_account_id LEFT JOIN transaction t ON t.ref_id=r.ref_id WHERE t.ref_id!='' AND (r.from_account_id=".Session::get('account_id')." OR r.to_account_id=".Session::get('account_id').") AND t.account_id=".Session::get('account_id')." group by t.ref_id ORDER BY t.txn_date DESC");
			return $trans->result_object();
		}
		return $user;
	}
	public function pending_transactions(){ $user = (Object)[];
		if(Session::get('is_login')){
			$trans = db()->query("SELECT u.*,r.*,(select us.username from users us INNER JOIN account ac ON ac.user_id=us.user_id where ac.account_id= r.to_account_id limit 1) as benificiary FROM users u INNER JOIN account a ON u.user_id=a.user_id LEFT JOIN reference r ON a.account_id = r.from_account_id LEFT JOIN transaction t ON t.ref_id!=r.ref_id WHERE t.ref_id='' group by r.ref_id ORDER BY a.account_id DESC");
			return $trans->result_object();
		}
		return $user;
	}
	public function all_transactions(){ $user = (Object)[];
		if(Session::get('is_login')){
			$trans = db()->query("SELECT u.*,r.*,(select us.username from users us INNER JOIN account ac ON ac.user_id=us.user_id where ac.account_id= r.to_account_id limit 1) as benificiary FROM users u INNER JOIN account a ON u.user_id=a.user_id LEFT JOIN reference r ON a.account_id = r.from_account_id LEFT JOIN transaction t ON t.ref_id=r.ref_id WHERE t.ref_id!='' group by t.ref_id ORDER BY a.account_id DESC");
			return $trans->result_object();
		}
		return $user;
	}
	public function account_holders(){ $user = (Object)[];
		if(Session::get('is_login')){
			#$user = db()->get_where("users",array("user_id"=>Session::get("user_id")))->row();
			$user = db()->query("SELECT * FROM users u LEFT JOIN account a ON u.user_id=a.user_id WHERE u.user_id!=".Session::get('user_id')." and user_type=1 ORDER BY a.account_id")->result();
		}
		return $user;
	}
	public function transaction_types(){ $types = (Object)[];
		if(Session::get('is_login')){
			#$user = db()->get_where("users",array("user_id"=>Session::get("user_id")))->row();
			$types = db()->query("SELECT * FROM transaction_type where status ='1'")->result();
		}
		return $types;
	}
	public function fund_transfer(){ $msg = 'failed'; $app = new Http();
		if(Session::get('is_login')){
			$p = Request::body();
			$account_id = $this->user_info()->account_id;
			$query = $app->db->query("INSERT INTO reference (`from_account_id`, `to_account_id`, `txt_type_id`, `amount`, `note`, `ref_date`, `status`) values ('".$account_id."','".$p->account_id."','".$p->txt_type_id."','".$p->amount."','".$p->note."',NOW(),1)");
			if($query){ 
				$ref_id = $app->db->insert_id();
				#db()->query("call approve_reference(".$ref_id.")");
				if($p->txt_type_id == 2){ # If IMPS Immediate transfer
					$ref = $app->db->query("SELECT to_account_id , amount FROM reference WHERE ref_id =".$ref_id)->row();
		
					$app->db->query("update `account` set available_balance = available_balance+".$ref->amount." , last_activity = NOW() WHERE account_id = ".$ref->to_account_id); 
	
					$app->db->query("INSERT INTO transaction (`ref_id`, `txn_amount`, `current_balance`, `txn_type`, `account_id`, `txn_type_id`, `txn_note`, `txn_date`) SELECT r.ref_id,r.amount,a.available_balance,'Debit',r.from_account_id,r.txt_type_id,r.note,now() FROM reference r INNER JOIN account a ON a.account_id=r.from_account_id WHERE r.ref_id=".$ref_id." order by r.ref_id DESC LIMIT 1");    
	
					$app->db->query("INSERT INTO transaction (`ref_id`, `txn_amount`, `current_balance`, `txn_type`, `account_id`, `txn_type_id`, `txn_note`, `txn_date`) SELECT r.ref_id,r.amount,a.available_balance,'Credit',r.to_account_id,r.txt_type_id,r.note,now() FROM reference r INNER JOIN account a ON a.account_id=r.to_account_id WHERE r.ref_id=".$ref_id." order by r.ref_id DESC LIMIT 1");
				}
				$msg = 'success';
			}
		}
		return $msg;
	}
	public function approve_transaction(){ $p = Request::body(); 
		$msg = 'failed';
		if(isset($p->ref_id) && $p->ref_id != NULL){ $ref_id = $p->ref_id;
			$ref = db()->query("SELECT to_account_id , amount FROM reference WHERE ref_id =".$ref_id)->row();
    
			db()->query("update `account` set available_balance = available_balance+".$ref->amount." , last_activity = NOW() WHERE account_id = ".$ref->to_account_id); 

			db()->query("INSERT INTO transaction (`ref_id`, `txn_amount`, `current_balance`, `txn_type`, `account_id`, `txn_type_id`, `txn_note`, `txn_date`) SELECT r.ref_id,r.amount,a.available_balance,'Debit',r.from_account_id,r.txt_type_id,r.note,now() FROM reference r INNER JOIN account a ON a.account_id=r.from_account_id WHERE r.ref_id=".$ref_id." order by r.ref_id DESC LIMIT 1");    

			db()->query("INSERT INTO transaction (`ref_id`, `txn_amount`, `current_balance`, `txn_type`, `account_id`, `txn_type_id`, `txn_note`, `txn_date`) SELECT r.ref_id,r.amount,a.available_balance,'Credit',r.to_account_id,r.txt_type_id,r.note,now() FROM reference r INNER JOIN account a ON a.account_id=r.to_account_id WHERE r.ref_id=".$ref_id." order by r.ref_id DESC LIMIT 1");
			
			$msg = 'success';
		}
		return $msg;
			
	}
	public function export_transaction(){
		
		 $objPHPExcel = new PHPExcel();
		
		 $objPHPExcel->getActiveSheet()->SetCellValue('A1', "Date");
		 $objPHPExcel->getActiveSheet()->SetCellValue('B1', "Remitter");
		 $objPHPExcel->getActiveSheet()->SetCellValue('C1', "Benificiary");
		 $objPHPExcel->getActiveSheet()->SetCellValue('D1', "Debit");
		 $objPHPExcel->getActiveSheet()->SetCellValue('E1', "Credit");
		 $objPHPExcel->getActiveSheet()->SetCellValue('F1', "Current Balance");
		 $objPHPExcel->getActiveSheet()->SetCellValue('G1', "Notes");
		 
		 $rowCount = 2;
		 $results = $this->transactions();
		 foreach($results as $row){
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row->txn_date);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row->username);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row->benificiary);
			$debit = ($row->txn_type=="Debit")?Request::label('rs').$row->txn_amount:'-';
			$credit = ($row->txn_type=="Credit")?Request::label('rs').$row->txn_amount:'-';
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $debit);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $credit);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, Request::label('rs').$row->current_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row->txn_note);
		  $rowCount++;
		}
		
		 header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		 header('Content-Disposition: attachment;filename="transactions.xlsx"');
		 header('Cache-Control: max-age=0');		
		 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		 $objWriter->save('php://output');
		
	}
}