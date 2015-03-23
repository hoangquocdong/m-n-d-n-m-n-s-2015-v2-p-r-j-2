<?php
/*
	LOAD MODUN
	$newtopic=load_modun('newtopic');
*/
	$mtpl = new Template($modun_template_path.'/default.html');
	$mtpl -> set('path',$modun_template_path);
	$modun_content = $mtpl->fetch();
?>
