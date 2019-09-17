<?php 
header("Content-type: text/html; charset=utf-8"); 
require('../config.php');
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD);
mysql_select_db(DB_NAME) or die('Could not select database');
mysql_query("SET NAMES UTF8");
require('../proxy_info.php');
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD);
mysql_select_db(DB_NAME) or die('order_Form Could not select database');
require('../common/utility_shop.php');


echo '--------测试开始-----------<br>';

$shopmessage 	= new shopMessage_Utlity(); 	//返佣、发信息、查找上一级


//测试发送短信信息
//$test->send_sns_msg(5171,13925513180,'3015921470205384',1);

//$test->send_mail_msg(5171,1,'3015921470205384',1);

//$shopmessage->SendMessage('', '', 5171,1,'3015921470205384',1);
$shopmessage->send_mail_msg(5171,1,'3015921470205384',1);

echo '--------测试结束-----------';
?>

