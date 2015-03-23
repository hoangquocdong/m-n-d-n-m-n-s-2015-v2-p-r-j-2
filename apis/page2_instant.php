<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$start = microtime(true);

$serial_meter =  isset($_REQUEST['serial'])? $_REQUEST['serial'] : '';

$st =  isset($_REQUEST['st'])? $_REQUEST['st'] : 0;

$et =  isset($_REQUEST['et'])? $_REQUEST['et'] : 0;

//$serial_meter = clean_text($serial_meter);

$serial_meter = '97815743';
  CONNECT_DB();
    mysql_query("SET NAMES utf8");


    /*
    *	Lây dư liệu bảng - chỉ số công tơ
    */


	if ($st == 0){
		$sql ='SELECT `i_pha_a`, `i_pha_b`, `i_pha_c`, `u_pha_a`, `u_pha_b`, `u_pha_c`, `p_pha_a`, `p_pha_b`, 
				`p_pha_c`, `p_sum`, `ppk_pha_a`, `ppk_pha_b`, `ppk_pha_c`, `ppk_sum`, `pbk_pha_a`, `pbk_pha_b`, `pbk_pha_c`, 
				`pbk_sum`, `cosphi_a`, `cosphi_b`, `cosphi_c`, `cosphi_mean`, `f_pha_a`, `f_pha_b`, `f_pha_c`, `full_time`, `full_time_d`  
				FROM `instan_value` WHERE `serial_meter` = "'.$serial_meter.'" ORDER BY ID DESC LIMIT 0,5';
	} else {
		$sql ='SELECT `i_pha_a`, `i_pha_b`, `i_pha_c`, `u_pha_a`, `u_pha_b`, `u_pha_c`, `p_pha_a`, `p_pha_b`, 
				`p_pha_c`, `p_sum`, `ppk_pha_a`, `ppk_pha_b`, `ppk_pha_c`, `ppk_sum`, `pbk_pha_a`, `pbk_pha_b`, `pbk_pha_c`, 
				`pbk_sum`, `cosphi_a`, `cosphi_b`, `cosphi_c`, `cosphi_mean`, `f_pha_a`, `f_pha_b`, `f_pha_c`, `full_time`, `full_time_d`  
				FROM `instan_value` WHERE `full_time_d` >= ".$st." AND  `full_time_d` <= ".$et." AND `serial_meter` = "'.$serial_meter.'" ORDER BY ID DESC LIMIT 0,1';
	}

	

	$result = mysql_query($sql) or die('0');

	$data = array();
	$du1 = array();
	$du2 = array();
	$du3 = array();

	$di1 = array();
	$di2 = array();
	$di3 = array();

	$df1 = array();
	$df2 = array();
	$df3 = array();

	while($row = mysql_fetch_array($result)){

		$fulltime = (int)$row['full_time_d']*1000;

		array_push($di1, array($fulltime,(float)$row['i_pha_a']));
        array_push($di2, array($fulltime,(float)$row['i_pha_b']));
        array_push($di3, array($fulltime,(float)$row['i_pha_c']));

        array_push($du1, array($fulltime,(float)$row['u_pha_a']));
        array_push($du2, array($fulltime,(float)$row['u_pha_b']));
        array_push($du3, array($fulltime,(float)$row['u_pha_c']));

        array_push($df1, array($fulltime,(float)$row['f_pha_a']));
        array_push($df2, array($fulltime,(float)$row['f_pha_b']));
        array_push($df3, array($fulltime,(float)$row['f_pha_c']));

		$tmp = array(
				'u_pha_a' => $row['u_pha_a'],
				'u_pha_b' => $row['u_pha_b'], 
				'u_pha_c' => $row['u_pha_c'], 
				'i_pha_a' => $row['i_pha_a'], 
				'i_pha_b' => $row['i_pha_b'], 
				'i_pha_c' => $row['i_pha_c'],
				'f_pha_a' => $row['f_pha_a'],
				'f_pha_b' => $row['f_pha_b'], 
				'f_pha_c' => $row['f_pha_c'],
				'cosphi_a' => $row['cosphi_a'],
				'cosphi_b' => $row['cosphi_b'], 
				'cosphi_c' => $row['cosphi_c'],
				'p_pha_a' => $row['p_pha_a'], 
				'p_pha_b' => $row['p_pha_b'],
				'p_pha_c' => $row['p_pha_c'], 
				'ppk_pha_a' => $row['ppk_pha_a'], 
				'ppk_pha_b' => $row['ppk_pha_b'],
				'ppk_pha_c' => $row['ppk_pha_c'], 
				'pbk_pha_a' => $row['pbk_pha_a'],
				'pbk_pha_b' => $row['pbk_pha_b'], 
				'pbk_pha_c' => $row['pbk_pha_c'], 
				'p_sum' => $row['p_sum'],
				'ppk_sum' => $row['ppk_sum'], 
				'pbk_sum' => $row['pbk_sum'], 
				'cosphi_mean' => $row['cosphi_mean'], 
				'full_time' => $row['full_time']
			);
		array_push($data,$tmp);
	}

	$du = array();
	$di = array();
	$df = array();

	array_push($du, $du1, $du2, $du3);
	array_push($di, $di1, $di2, $di3);
	array_push($df, $df1, $df2, $df3);

	$rs = array(
		'dienap' => $du,
		'dongdien' => $di,
		'tanso' => $df,
		'dulieu' => $data
		);

	//echo $time_elapsed_us = microtime(true) - $start;

	echo json_encode($rs);
	//echo json_encode($tmp2);
	CLOSE_DB();

	$vars = array_keys(get_defined_vars());
	for ($i = 0; $i < sizeOf($vars); $i++) {
	    unset($$vars[$i]);
	}
	unset($vars,$i);
          
?> 
