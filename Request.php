<?php
if(!defined('SHA')) die("Access Denied");
class Request{
	public function __construct($args=array()){
		foreach($args as $var=>$value){
			$this->$var = $value;
		}
		$this->session = new Session;
	}
	# Methods
	public static function request($variable=NULL){
		$_= $_REQUEST;
		if(trim($variable)!="" && isset($_REQUEST[$variable])){
			$_ = htmlspecialchars(trim($_REQUEST[$variable]));
		}elseif($_SERVER['REQUEST_METHOD'] == 'REQUEST'){
			$_= $_REQUEST;
		}  return $_;
	}
	public static function get($variable=NULL){
		$_= $_GET;
		if(trim($variable)!="" && isset($_GET[$variable])){
			$_ = htmlspecialchars(trim($_GET[$variable]));
		}elseif($_SERVER['REQUEST_METHOD'] == 'GET'){
			$_= $_GET;
		} return $_;
	}
	public static function post($variable=NULL){
		$_= '';
		if(trim($variable)!="" && isset($_POST[$variable])){
			$_ = htmlspecialchars(trim($_POST[$variable]));
		}elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
			$_= $_POST;
		} return $_;
	}
	public static function unset_var($variable=NULL){		
		if(trim($variable)!=""){
			unset($variable);
		}
	}
	public static function unset_post($variable=NULL){		
		if(trim($variable)!="" && isset($_POST[$variable])){
			unset($_POST[$variable]);
		}else{
			unset($_POST);
		}
	}
	public static function unset_get($variable=NULL){		
		if(trim($variable)!="" && isset($_GET[$variable])){
			unset($_GET[$variable]);
		}else{
			unset($_GET);
		}
	}
	public static function is_post($variable=NULL){  $post = [];
		$_= false; $exps= explode(",", $variable);
		foreach ($exps as $value){
			$post[] = isset($_POST[$value])?true:false;
		}
		if(!in_array(false, $post)){
			$_ = true;
		}
		return $_;
	}
	public static function is_get($variable=NULL){  $get = [];
		$_= false; $exps= explode(",", $variable);
		foreach ($exps as $value){
			$get[] = isset($_GET[$value])?true:false;
		}
		if(!in_array(false, $get)){
			$_ = true;
		}
		return $_;
	}
	public static function args($variable=NULL){
		$_= '';
		$input = file_get_contents('php://input');
		if(trim($variable)!="" && isset($input) && $input!=''){
			$requestParams = json_decode($input, true);
			$_ = $requestParams[$variable];
		}elseif(trim($variable)==""){
			$_= $requestParams = json_decode($input, true);
		} return $_;
	}
	public static function body(){
		return json_decode(file_get_contents("php://input"));
	}
	public static function redirect($url=NULL){
		if(trim($url)!=NULL){
			echo '<script>document.location.href="'.$url.'";</script>';
		}
	}
	public static function location($url=NULL){
		if(trim($url)!=NULL){
			ob_start();
			header("Location: ".$url);
		}
	}
	public static function label(){ $args = func_get_args();
		# language must be a session dynamic variable
		$en = (Session::get("lang")!="")?Session::get("lang"):"en";
		if(is_dir(LANG_PATH.$en)){
			foreach ( glob(LANG_PATH.$en."/*.php") as $file){
			        if($file != "") require $file;
			}
			if(isset($lang)){
				if(count($args) == 1){
					return (isset($lang[$args[0]])) ? $lang[$args[0]] : '';
				}else if(count($args) >= 2){
					$key = $args[0];
					array_shift($args);
					return  (isset($lang[$key])) ? vsprintf($lang[$key],$args) : '';
				}
			}
			
		}

	}
}
