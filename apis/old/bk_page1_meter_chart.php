<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$serial_meter =  isset($_REQUEST['serial'])? $_REQUEST['serial'] : '';

$serial_meter = clean_text($serial_meter);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

    //$sql='SELECT `serial_meter`, `name_meter`, `level_meter`, `relation_meter`, `online_status`  FROM `meter` WHERE `id_sub` = '.$id_sub.' ORDER BY `relation_meter` DESC';

    $sql = 'SELECT `i_pha_a`, `i_pha_b`, `i_pha_c`, `full_time_h` FROM `instan_value` WHERE `serial_meter`= "'.$serial_meter.'" ORDER BY ID DESC LIMIT 0,30';
    //echo $sql;
    $result = mysql_query($sql) or die('0');

    $data = array();
	$data1 = array();
	$data2 = array();
	while($row = mysql_fetch_array($result)){
		$tmp1 = array('full_time_h' => $row['full_time_h']);
		$tmp2 = array(
				'i_pha_a' => $row['i_pha_a']
				//'i_pha_b' => $row['i_pha_b'], 
				//'i_pha_c' => $row['i_pha_c']
			);
		array_push($data1, $row['full_time_h']);
		array_push($data2, $row['i_pha_a']);
	}
	array_push($data, $data1, $data2);
	echo json_encode($data);

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 
