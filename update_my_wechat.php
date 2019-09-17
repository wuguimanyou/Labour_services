<?php
header("Content-type: text/html; charset=utf-8"); 
require('../config.php');
require('../customer_id_decrypt.php'); //导入文件,获取customer_id_en[加密的customer_id]以及customer_id[已解密]
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD);
mysql_select_db(DB_NAME) or die('Could not select database');
//require('../common/utility.php');
//require('../common/utility_shop.php');
// $user_id = 194515;
// $customer_id = 3243;
//$utlity_common = new shopMessage_Utlity();
//$user_id = $utlity_common->web_Authorization($customer_id,$from);
/* 
if(!empty($_SESSION["user_id_".$customer_id])){			//用户ID
	$user_id   = $_SESSION["user_id_".$customer_id];
}
$user_id   = passport_decrypt((string)$user_id);

 
if(!empty($_SESSION["from_type_".$customer_id])){
	$from_type = $_SESSION["from_type_".$customer_id];  //从哪里进来 0:网页 1:微信 2:APP 3:支付宝	
}

if(!empty($_SESSION["is_bind_".$customer_id])){
	$is_bind   = $_SESSION["is_bind_".$customer_id];    //用户是否绑定了手机号码
}
if(!empty($_SESSION["fromuser_".$customer_id])){
	$fromuser  = $_SESSION["fromuser_".$customer_id];    //用户标识
} */

$_SSSION["user_id_".$customer_id] = "";
$_SESSION["myfromuser_".$customer_id] = "";
$_SESSION["fromuser_".$customer_id]   = "";
	
/* echo $_SESSION["
".$customer_id].'==<br/>';
echo $_SESSION["fromuser_".$customer_id].'==<br/>';
echo $_SESSION["user_id_".$customer_id].'==<br/>'; */

/* $time		 = time();
$exp_time 	 = time()+(2*60*60);
//echo $time;die;

$query = "SELECT m.access_token,m.appid,m.appsecret,m.expires_token_time,u.weixin_fromuser FROM weixin_menus m left join weixin_users u on m.customer_id=u.customer_id WHERE u.id=".$user_id;
$result= mysql_query($query);
while( $row = mysql_fetch_object($result) ){
	$access_token  		= $row->access_token;
	$openid 			= $row->weixin_fromuser;
	$expires_token_time = $row->expires_token_time;
	$appid 				= $row->appid;
	$appsecret 			= $row->appsecret;
}
//判断access_token是否过期，如果过期则从新获取
	if( $expires_token_time == '' || $expires_token_time <= $time || $access_token == '' ){
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, "");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            $info = curl_exec($ch); 
            curl_close($ch); 
            $obj=json_decode($info,true);           
            $access_token = $obj['access_token'];
            $query = "UPDATE weixin_menus SET access_token='$access_token',get_token_time='$time',expires_token_time='$exp_time'  where customer_id=".$customer_id;
            mysql_query($query);
	}


	//根据openid跟access_token 重新获取用户信息
	$t_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";	
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $t_url); 
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, "");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$info = curl_exec($ch); 
	curl_close($ch); 
	$obj=json_decode($info,true);

	$fromuser = $obj['openid'];
	$headimg  = $obj['headimgurl'];
	$username = $obj['nickname'];
	$city     = $obj['city'];
	$province = $obj['province'];
	$country  = $obj['country'];
	$_SESSION["myfromuser_".$customer_id] = 
	$query = "UPDATE weixin_users SET weixin_name='$username',weixin_headimgurl='$headimg',country='$country',province='$province',city='$city' WHERE id=$user_id";
	//echo $query;
	$result= mysql_query($query)or die('Query failed68: ' . mysql_error());
	setcookie("login_headimgurl",$headimg, time()+604800);//设置用户头像COOKIE */

//$url = "my_data.php?customer_id=".passport_encrypt($customer_id);
//echo '<script>window.location.href($url)</script>';



?>

<!DOCTYPE html>
<html>
<head>
    <title>刷新中</title>
    <!-- 模板 -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta content="no" name="apple-touch-fullscreen">
    <meta name="MobileOptimized" content="320"/>
    <meta name="format-detection" content="telephone=no">
    <meta name=apple-mobile-web-app-capable content=yes>
    <meta name=apple-mobile-web-app-status-bar-style content=black>
    <meta http-equiv="pragma" content="nocache">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8">
    <!-- 模板 -->
    
</head>
<body>
</body>
<script>
	window.location.href="my_data.php?customer_id=<?php echo $customer_id_en;?>";
</script>
</html>