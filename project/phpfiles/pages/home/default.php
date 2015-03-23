<?
	/*
		*Lấy dữ liệu nhét vào bảng
	*/	
	
	session_start();
	//if (!$_SESSION['memberloggedin']) header('Location: index.php?page=login');//ko cho vào trực tiếp, khi chưa đăng nhập thì tự động chuyển về trang đăng nhập	
	//$_SESSION['page1']='home';

	
	CONNECT_DB();
		mysql_query("SET NAMES utf8");
		

		/****************************************************************
		===========================LEFT PANEL============================

			Vòng lặp lớn - list tên các điện lực ra
			Nếu usergroup
				0: 	admin => Tất - list hết các điện lực, thủy điện ra 
				1: 	Chỉ 1 điện lực - Điện lực hoặc thủy điện 
					=> đều list công tơ của thủy điện trong 1 điện lực ra
		*****************************************************************/
		$usergroup = (int)$_SESSION['usergroup']; 
		$id_investor = (int)$_SESSION['id_investor']; 
		$id_pwc = (int)$_SESSION['id_pwc']; 
		$id_sub = (int)$_SESSION['id_sub'];
		//$ma_congto = $_SESSION['ma_congto'];

		$tbl_name1="power_company"; // Table name 
		
		if ($usergroup==0) {	
			$sql="SELECT * FROM $tbl_name1";
			$id_pwc=1;
		} else {
			$sql="SELECT * FROM $tbl_name1 WHERE id_pwc = $id_pwc";
		}
		
		$result=mysql_query($sql);

		$dienluc= array();
		while($rows=mysql_fetch_array($result)){
			$dienluc[]=$rows;
		}
		//echo '<pre>';print_r($dienluc);echo '</pre>';echo '<br />';

		/****************************************************************
		Vòng lặp nhỏ - list tên các thủy điện thuộc từng điện lực ra
		****************************************************************/
		//$tbl_name="substation_power"; // Table name 
		//$thuydien= array();
		$sub_index = array();
		$meter_index = array();
		$subnames = array();
		$meters= array();
		//echo '<br />'.count($dienluc).'<br />';
		//echo $id_investor;
		$countdienluc=0;
		foreach ($dienluc as $dienlucs) {
			//echo " <br />".$dienlucs['id_pwc']."  :  ".$countdienluc." <br />";
			$idpwc = $dienlucs['id_pwc'];
			//echo $dienlucs[id_pwc];
			if ($usergroup==0){				
				$sqlsub="SELECT * FROM substation_power WHERE id_pwc = $idpwc";
			}
			if ($usergroup==1){
				if ($id_investor==0){
					$sqlsub="SELECT * FROM substation_power WHERE id_pwc = $idpwc";
				} else {
					$sqlsub="SELECT * FROM substation_power WHERE id_pwc = $idpwc AND id_investor = $id_investor";
				}
			}
			if ($usergroup==2){
				if ($id_investor==0){
					$sqlsub="SELECT * FROM substation_power WHERE id_pwc = $idpwc AND id_sub = $id_sub";
				} else {
					$sqlsub="SELECT * FROM substation_power WHERE id_pwc = $idpwc AND id_investor = $id_investor AND id_sub = $id_sub";
				}
			}
			$result=mysql_query($sqlsub);

			if ($result){
				$countsub=0;
				while($rows=mysql_fetch_array($result)){
					$subnames[$countdienluc][]=$rows;
					$countsub++;
				}	
				$sub_index[$countdienluc]=$countsub;	
			} else {
				echo 'sql error'; exit();
			}			

			$countdienluc++;
			//$id_dienluc++;
		}
		//echo $countdienluc;
		//echo '<pre>';print_r($subnames);echo '</pre>';echo '<br />';//exit();
		
		$leng_dl = sizeof($dienluc);
		//echo $leng_sub = sizeof($subnames); //exit();
		$count_online=0;
		$count_offline=0;
		$count_nmtd=0;
		for ($i=0; $i < $leng_dl; $i++) { 
			//echo $sub_index[0].'<br />';
			for ($j=0; $j < $sub_index[$i]; $j++) { 
				//echo $i.$j.'<br />';
				$count_nmtd++;
				if (isset($subnames[$i][$j][3])) {
					$idsubstation = $subnames[$i][$j][3];
					$sqlmeter="SELECT * FROM meter WHERE id_sub = $idsubstation";
					$result=mysql_query($sqlmeter);
					$countmeter = 0;
					if ($result){					
						while($rows=mysql_fetch_array($result)){
							//echo '<pre>';print_r($rows);echo '</pre>';echo '<br />';
							$meters[$i][$j][]=$rows;
							$countmeter++;
							if ($rows[15]==1) {$count_online++;}  
							else $count_offline++;
							//echo $rows[15];
						}	
						$meter_index[$i][$j]=$countmeter;
					} else {
						echo 'sql error'; exit();
					}
				}
			}
		}	
		//echo $count_online.' :: '.$count_offline;
		//echo $meters;
		//echo $meters[0][0][1][5];
		//echo '<pre>';print_r($meters);echo '</pre>';echo '<br />';
		/*
			====================END OF LEFT PANEL============================		
		*/
/*
	LOAD PAGE
*/
/*
	KHU VỰC PHẢI THAY ĐỔI - KHỞI TẠO - KHỞI TẠO CÁC BIẾN CẦN DÙNG
	khai báo các biến cần truyền cho pages như title, list, nội dung các modun khác...
*/
	global $_content;
	$page_tile = 'EVN Monitoring Program Homepage!';       			//Title của trang đó
	$header=load_modun('header');									//truyền các biến cần thiết cho template

/*
	KHU VỰC KHÔNG THAY ĐỔI - TỰ ĐỘNG - PHẦN TỰ ĐỘNG
	gọi template ra - phần này tự động sử dụng các biến truyền vào.
*/
	$cctpl = new Template($page_template_path.'/default.html');
/*
	KHU VỰC PHẢI THAY ĐỔI THEO CÁC BIẾN ĐÃ KHỞI TẠO/ KHAI BÁO
	Truyền các biến vào nội dung page
*/
	$cctpl -> set('tile',$page_tile);
	$cctpl -> set('path',$page_template_path);
	$cctpl -> set('php_path',$page_phpfiles_path);
	$cctpl -> set('header',$header);
	$cctpl -> set('dienluc',$dienluc);
	$cctpl -> set('meters',$meters);
	$cctpl -> set('sub_index',$sub_index);
	$cctpl -> set('meter_index',$meter_index);
	$cctpl -> set('count_online',$count_online);
	$cctpl -> set('count_offline',$count_offline);
	$cctpl -> set('countdienluc',$count_nmtd);
	
	//echo $page_content = $cctpl->fetch();		//echo tại file include
	$_content = $cctpl->fetch();				//echo tại file index.php
?>

