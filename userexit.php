<?php 
header("Content-type: text/html; charset=utf-8"); 
require('../config.php');
require('../customer_id_decrypt.php'); //导入文件,获取customer_id_en[加密的customer_id]以及customer_id[已解密]
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD);
mysql_select_db(DB_NAME) or die('Could not select database');
require('../common/utility.php');

//头文件----start
require('../common/common_from.php');
//头文件----end
$user_id=-1;//用户ID
if(!empty($_POST["user_id"])){
	$user_id = $configutil->splash_new($_POST["user_id"]);
}

$_SESSION["user_id_".$customer_id]		=-1;
$_SESSION["myfromuser_".$customer_id]	="";
$_SESSION["fromuser_".$customer_id]		="";
$_SESSION["is_bind_".$customer_id]		=0;//取消注册
$_SESSION["customer_id"]				="";

echo '<script>window.location.href="login.php?customer_id='.$customer_id_en.'";</script>';
?>