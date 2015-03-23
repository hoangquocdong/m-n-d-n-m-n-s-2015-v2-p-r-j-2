<?php

function menuleftget($id, $token){

	$returnvalue = '';

	$sql = 'SELECT `cache` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id.' AND `flag_change` = 0';
	$result = mysql_query($sql) or die('0');
	
	if (mysql_num_rows($result)){
		$rows = mysql_num_rows($result);
		if ($rows == 1) { 
			while($rsl=mysql_fetch_array($result)){
				$val = $rsl['cache'];
		    }
		    $returnvalue = $val;
		} else {
			$returnvalue = json_encode(amrupdatecache($id, $token));
			$menu = updatecache($id, $token);
		}
	} else {
		$returnvalue = json_encode(amrupdatecache($id, $token));
		$menu = updatecache($id, $token);
	}

	return $returnvalue;
}

function menuleftgetall($id, $token){

	$returnvalue = '';

	$sql = 'SELECT `cacheall` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id.' AND `flag_change` = 0';
	$result = mysql_query($sql) or die('0');
	
	if (mysql_num_rows($result)){
		$rows = mysql_num_rows($result);
		if ($rows == 1) { 
			while($rsl=mysql_fetch_array($result)){
				$val = $rsl['cacheall'];
		    }
		    $returnvalue = $val;
		} else {
			$returnvalue = json_encode(updatecache($id, $token));
			$menu = amrupdatecache($id, $token);
		}
	} else {
		$returnvalue = json_encode(updatecache($id, $token));
		$menu = amrupdatecache($id, $token);
	}

	return $returnvalue;
}


function amrupdatecache($id, $token){

		$valuereturn = array();

		$listpc = amrgetallpc();

		$sql = 'SELECT `permission` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id;//.' AND `flag_change` = 1';
		
		$result = mysql_query($sql) or die('0');
		
		$permission ='';	//init value to json_encode function

		while($rsl=mysql_fetch_array($result)){
			$permission = $rsl['permission'];
	    }
	    $permission = json_decode($permission);
	    
	    /*
	    * for theo điện lực có quyền xem - permission
	    */
	    for ($i=0; $i<sizeof($permission); $i++){

	    	$tmp0 = array(); $tmp1 = array();
	    	$pckey = 'idpc_'.$permission[$i][0];

	    	if (isset($listpc[$pckey])){

	    		$pcitem = array();
	    		$subitem = array();

	    		$sublist = amrgetsublist($permission[$i][0]);
	    		for ($j=0; $j<sizeof($permission[$i][1]); $j++){

	    			$subkey = 'idsub_'.$permission[$i][1][$j];

	    			if (isset($sublist[$subkey])){
	    				array_push($subitem, $sublist[$subkey]);
	    			}
	    		}
	    		array_push($pcitem, $listpc[$pckey], $subitem);
	    		array_push($valuereturn, $pcitem);
	    	}
	    }

	    $menu = json_encode($valuereturn);
		amrgupdate_cache_menu($menu, $token, $id);
	    return $valuereturn;
	}

	function amrgetallpc(){

		$sql='SELECT `id_pwc`, `name_pwc` FROM `power_company` WHERE `status` = 1 ORDER BY `id_orderby` ASC'; 
		$result = mysql_query($sql) or die('0');

		$data = array();
		while($row = mysql_fetch_array($result)){

			$tmp = array(
					'id_pwc' => $row['id_pwc'], 
					'name_pwc' => $row['name_pwc']
				);

			$key = 'idpc_'.$row['id_pwc'];
			$data[$key] = $tmp;
		}
		

		return $data;
	}


	function amrgetsublist($pcid){

		$sub = array();

		    $sql=' SELECT `id_sub`, `name_sub`, `connection_sub` FROM `substation_power` WHERE `status` = 1 AND `id_pwc` = '.$pcid;//.' AND `status` = 1'; 
		    $result = mysql_query($sql) or die('0');

			while($row = mysql_fetch_array($result)){
				$tmp = array(
						'id_sub' => $row['id_sub'], 
						'name_sub' => $row['name_sub'],
						'online' => $row['connection_sub']
					);
				$key = 'idsub_'.$row['id_sub'];
				$sub[$key] = $tmp;
			}

		return $sub;
	}

	function amrgupdate_cache_menu($str, $token, $id){
		$value = mysql_real_escape_string($str);
		$sql = 'UPDATE `user` SET `cache`="'.$value.'", `flag_change`=0 WHERE token="'.$token.'" AND ID = '.$id;
    	$result = mysql_query($sql) or die('0');
	}


	function updatecache($id, $token){

		$valuereturn = array();

		$listpc = getallpc();

		$sql = 'SELECT `permission` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id;//.' AND `flag_change` = 1';
		
		$result = mysql_query($sql) or die('0');
		
		$permission ='';	//init value to json_encode function

		while($rsl=mysql_fetch_array($result)){
			$permission = $rsl['permission'];
	    }
	    $permission = json_decode($permission);
	    
	    /*
	    * for theo điện lực có quyền xem - permission
	    */
	    for ($i=0; $i<sizeof($permission); $i++){

	    	$tmp0 = array(); $tmp1 = array();
	    	$pckey = 'idpc_'.$permission[$i][0];

	    	if (isset($listpc[$pckey])){

	    		$pcitem = array();
	    		$subitem = array();

	    		$sublist = getsublist($permission[$i][0]);
	    		for ($j=0; $j<sizeof($permission[$i][1]); $j++){

	    			$subkey = 'idsub_'.$permission[$i][1][$j];

	    			if (isset($sublist[$subkey])){
	    				array_push($subitem, $sublist[$subkey]);
	    			}
	    		}
	    		array_push($pcitem, $listpc[$pckey], $subitem);
	    		array_push($valuereturn, $pcitem);
	    	}
	    }

	    $menu = json_encode($valuereturn);
		update_cache_menu($menu, $token, $id);
	    return $valuereturn;
	}

	function getallpc(){

		$sql='SELECT `id_pwc`, `name_pwc` FROM `power_company` WHERE 1 ORDER BY `id_orderby` ASC'; 
		$result = mysql_query($sql) or die('0');

		$data = array();
		while($row = mysql_fetch_array($result)){

			$tmp = array(
					'id_pwc' => $row['id_pwc'], 
					'name_pwc' => $row['name_pwc']
				);

			$key = 'idpc_'.$row['id_pwc'];
			$data[$key] = $tmp;
		}
		

		return $data;
	}


	function getsublist($pcid){

		$sub = array();

		    $sql=' SELECT `id_sub`, `name_sub`, `connection_sub` FROM `substation_power` WHERE `id_pwc` = '.$pcid;//.' AND `status` = 1'; 
		    $result = mysql_query($sql) or die('0');

			while($row = mysql_fetch_array($result)){
				$tmp = array(
						'id_sub' => $row['id_sub'], 
						'name_sub' => $row['name_sub'],
						'online' => $row['connection_sub']
					);
				$key = 'idsub_'.$row['id_sub'];
				$sub[$key] = $tmp;
			}

		return $sub;
	}

	function update_cache_menu($str, $token, $id){
		$value = mysql_real_escape_string($str);
		$sql = 'UPDATE `user` SET `cacheall`="'.$value.'", `flag_change`=0 WHERE token="'.$token.'" AND ID = '.$id;
    	$result = mysql_query($sql) or die('0');
	}

?>