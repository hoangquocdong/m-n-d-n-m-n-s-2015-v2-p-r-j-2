<?
session_start();



require "libs/config.php";
require "libs/class_Template.php";
require "libs/common_functions.php";
require "libs/db_functions.php";
require "libs/custom_functions.php";

global $_content, $_menu, $_modun, $userid, $token;

$userid = 0;$token = 0;
if (isset($_SESSION['token'])) {$token = $_SESSION['token'];}
if (isset($_SESSION['id'])) {$userid = $_SESSION['id'];}
$page =  (isset($_REQUEST['page'])) ? $_REQUEST['page']: 'login';

if($page==''||$page==null){$page= 'login';}

	switch ($page){		
		case 'login':
		loadpage($page);
		break;
		case 'doxa':
		loadpage($page);
		break;

		default:
			$_content = '<h3 style="color:red">The page '.$page.' does not exist!</h3>';
		break;
	}
echo $_content;
?>