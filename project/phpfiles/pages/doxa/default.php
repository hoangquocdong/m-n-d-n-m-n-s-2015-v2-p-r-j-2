<?
	/*
		*Lấy dữ liệu nhét vào bảng
	*/	
	
	//session_start();
	if (!$_SESSION['login']) header('Location: index.php?page=login');//ko cho vào trực tiếp, khi chưa đăng nhập thì tự động chuyển về trang đăng nhập	
	//$_SESSION['page1']='home';

	
	CONNECT_DB();
		mysql_query("SET NAMES utf8");
		
		//$userid = 0;$token = 0;
		//if (isset($_SESSION['token'])) {$token = $_SESSION['token'];}
		//if (isset($_SESSION['id'])) {$userid = $_SESSION['id'];}

		global $userid, $token;

		$menuleft = menuleftget($userid, $token);



	/*
		LOAD PAGE
	*/
	/*
		KHU VỰC PHẢI THAY ĐỔI - KHỞI TẠO - KHỞI TẠO CÁC BIẾN CẦN DÙNG
		khai báo các biến cần truyền cho pages như title, list, nội dung các modun khác...
	*/
	global $_content;
	$page_tile = 'EVN Monitoring Program Homepage!';       			//Title của trang đó

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
	$cctpl -> set('menuleft',$menuleft);
	$cctpl -> set('userid',$userid);
	$cctpl -> set('token',$token);
	$cctpl -> set('page_template_path',$page_template_path);

	
	//echo $page_content = $cctpl->fetch();		//echo tại file include
	$_content = $cctpl->fetch();				//echo tại file index.php
?>

