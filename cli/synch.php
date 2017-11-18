<?php # sh app

$data = json_decode(file_get_contents("php://input"));
if($data->type!="" && $data->dest!="" && $data->content!=""){
	$type = $data->type;
	$file = "../".$type."/".ucfirst($data->dest).".php";
	if(file_exists($file)) die(clean_color("\033[0;31mSorry, ".ucfirst($type)." ".ucfirst($data->dest).".php already exist. \033[0m"));
	$content= base64_decode($data->content);
	if($type == 'api'){
		if(is_dir("../extender/sh")) { $uold = umask(0); chmod("../extender/sh",0777,true); umask($uold); }else{
			$uold = umask(0); mkdir("../extender/sh",0777,true); umask($uold);
		}
		$file = "../extender/sh/".ucfirst($data->dest).".php";
		if(file_put_contents($file,$content)){
			echo clean_color("\033[0;32m".ucfirst($type).' '.ucfirst($data->dest).'.php has been moved to server'."\033[0m");
		}else{
			echo clean_color("\033[0;31mSorry, ".ucfirst($type)." directory permission denied. \033[0m");
		}
	}else if($type == 'module'){ $uold = umask(0); chmod("../modules",0777,true); umask($uold);
		
	}else{ $uold = umask(0); chmod("../".$type,0777,true); umask($uold);
		if(file_put_contents($file,$content)){
			echo clean_color("\033[0;32m".ucfirst($type).' '.ucfirst($data->dest).'.php has been moved to server'."\033[0m");
		}else{
			echo clean_color("\033[0;31mSorry, ".ucfirst($type)." directory permission denied. \033[0m");
		}
	}
}


function BAD_FORMAT(){
	return clean_color("\033[0;31msh-service bad format command.\033[0m");
}
function clean_color($str){ 
	 $codes = array("\033[0;32m", "\033[0m", "\033[0;31m","\033[0m","\033[1;33m","\033[0;37m","\033[0;33m","\033[1;33m","\033[43m");
	 $rcodes = array("","","","","","","","","");
	 if(strtoupper(substr(PHP_OS, 0, 3)) != 'LIN'){ 
	 	$str = str_replace($codes,$rcodes, $str);
	 } return $str;
}
