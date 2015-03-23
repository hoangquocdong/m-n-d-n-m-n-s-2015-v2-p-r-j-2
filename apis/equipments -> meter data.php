<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$id =  isset($_REQUEST['id'])? $_REQUEST['id'] : 0;
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$value =  isset($_REQUEST['value'])? $_REQUEST['value'] : '';


$start = microtime(true);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

    $mnleft = getmenuleft($token, $id);

	function getmenuleft($token, $id){

		$return='';

		$sql = 'SELECT `cache` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id;
		$result = mysql_query($sql) or die('0');
		
		if (mysql_num_rows($result)){
			$rows = mysql_num_rows($result);
			if ($rows == 1) { 
				while($rsl=mysql_fetch_array($result)){
					$val = $rsl['cache'];
			    }
			    $return = $val;
			} else {
				$return = '';
			}
		} else {
			$return = '';
		}

		return json_decode($return);
	}

	function getmeterinfo($idsub){
		$idsub = (int)$idsub;
		$sql='SELECT `ID`, `serial_meter` FROM `meter` WHERE `id_sub` = '.$idsub; 
		$result = mysql_query($sql) or die('0');
		$data = array();
		while($row = mysql_fetch_array($result)){

			$tmp = array(
					'id' => $row['ID'], 
					'serial_meter' => $row['serial_meter']
				);

			array_push($data, $tmp);
		}
		return $data;
	}

	function getlistmeter($token, $id){

		$mnleft = getmenuleft($token, $id);

		$idsub = 'id_sub';$property = 'meterinfo';
		for ($i=0; $i<sizeof($mnleft); $i++){
			for ($j=0; $j<sizeof($mnleft[$i][2]); $j++){
				$sid = $mnleft[$i][2][$j] ->$idsub;
				$meterinfo = getmeterinfo($sid);
				$mnleft[$i][2][$j] ->$property = $meterinfo;
			}
		}
		echo json_encode($mnleft);
	}

	getlistmeter($token, $id);

	//echo $time_elapsed_us = microtime(true) - $start;

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 