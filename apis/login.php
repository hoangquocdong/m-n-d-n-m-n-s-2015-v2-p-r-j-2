<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$username =  isset($_REQUEST['username'])? $_REQUEST['username'] : '';
$password = isset($_REQUEST['password'])? $_REQUEST['password'] : '';

$username = clean_text($username);
$password = clean_text($password);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

     $token = $password.' - '.date("d/m/y H:i:s"); 
     $token = substr(MD5($token),0,10);
	 $sql='SELECT ID, full_name, phone_number, email FROM user WHERE password = "'.MD5($password).'" AND user_name = "'.$username.'" AND enable = 1';

	$rows = 0;$rs=array();
	
	if ($result=mysql_query($sql)){
		$rows = mysql_num_rows($result);
	}
	
	if ($rows == 1){

		while($rsl=mysql_fetch_array($result)){

			$rs=array(
				"link" => 'index.php?page=doxa&token='.$token,
				"id" => $rsl['ID'],
				"fullname" => $rsl['full_name'],
				"phone" => $rsl['phone_number'],
				"email" => $rsl['email'],
				"token" => $token		
			);

			$_SESSION['id']=$rsl['ID'];
			$_SESSION['fullname']=$rsl['full_name'];
			$_SESSION['phone']=$rsl['phone_number'];
			$_SESSION['email']=$rsl['email'];
			$_SESSION['token']=$token;
			$_SESSION['login']=1;

	    }
	    echo json_encode($rs);
		$sql = 'UPDATE `user` SET `token`="'.$token.'" WHERE ID = '.$rs['id'];
		$result = mysql_query($sql) or die('0');
	} else { echo "0";}

	CLOSE_DB();
	unset($sql, $result, $rs, $rows);
          
?> 