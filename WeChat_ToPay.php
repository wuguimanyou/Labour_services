<?php
header("Content-type: text/html; charset=utf-8"); 
require('../../../config.php');
require('../../../customer_id_decrypt.php'); //导入文件,获取customer_id_en[加密的customer_id]以及customer_id[已解密]
require('../../../back_init.php');
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD);
mysql_select_db(DB_NAME) or die('Could not select database');
require('../../../proxy_info.php');
include("../../../mshop/WeChatPay/weipay_new/WxPayPubHelper/WxPayPubHelper.php");
//此文件为零钱提现触发文件

mysql_query("SET NAMES UTF8");
$customer_id = passport_decrypt($customer_id);

$user_id = $configutil->splash_new($_GET["uid"]);
$key_id  = $configutil->splash_new($_GET["kid"]);
$batchcode  = $configutil->splash_new($_GET["b"]);

//echo $customer_id."==".$key_id."==".$batchcode."==".$user_id;





?>