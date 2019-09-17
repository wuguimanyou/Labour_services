<?php

header("Content-type: text/html; charset=utf-8");     
require('../config.php');
require('../customer_id_decrypt.php'); //导入文件,获取customer_id_en[加密的customer_id]以及customer_id[已解密]
//require('../back_init.php'); 
require('../common/utility_fun.php');
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD); 
mysql_select_db(DB_NAME) or die('Could not select database');

//头文件----start
require('../common/common_from.php');
//头文件----end

?>

<!DOCTYPE html>
<html>
<head>
    <title>会员卡管理</title>
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
    
    <link type="text/css" rel="stylesheet" href="./assets/css/amazeui.min.css" />
    <link type="text/css" rel="stylesheet" href="./css/order_css/global.css" />    
    
    <script type="text/javascript" src="./assets/js/jquery.min.js"></script>    
    <script type="text/javascript" src="./assets/js/amazeui.js"></script>
    <script type="text/javascript" src="./js/global.js"></script>
    <script type="text/javascript" src="./js/loading.js"></script>
    
    <script src="./js/jquery.ellipsis.js"></script>
    <script src="./js/jquery.ellipsis.unobtrusive.js"></script>
    
    <link rel="stylesheet" id="wp-pagenavi-css" href="./css/list_css/pagenavi-css.css" type="text/css" media="all">
	<link rel="stylesheet" id="twentytwelve-style-css" href="./css/list_css/style.css" type="text/css" media="all">
    <link rel="stylesheet" id="twentytwelve-style-css" href="./css/goods_css/dialog.css" type="text/css" media="all">
	<script src="./js/r_global_brain.js" type="text/javascript"></script>
	<script type="text/javascript" src="./js/r_jquery.mobile-1.2.0.min.js"></script>
    <script src="./js/sliding.js"></script>
	<link type="text/css" rel="stylesheet" href="./css/list_css/r_style.css" />
    <link type="text/css" rel="stylesheet" href="./css/password.css" />
    <link type="text/css" rel="stylesheet" href="./../card/css/card.css" />
    
<style>  
   .selected{border-bottom: 5px solid black; color:black; }
   .list {margin: 10px 5px 0 3px;	overflow: hidden;}
   .area-line{height:25px;width:1px;float:left;margin-top: 10px;padding-top: 20px;border-left:1px solid #cdcdcd;}
   .topDivSel{width:100%;height:45px;top:50px;padding-top:0px;background-color:white;}
   .infoBox{width:90%;margin:10px auto;;background-color:white;color:white;box-shadow: 3px 5px 3px #888888;position: relative;}
   .infoBox .ele{height: 40px;width:90%;line-height: 40px;margin:0 auto;}
   .red{color:red;}
   .black{color:black}
   .content_top{height: 45px;line-height:45px;background-color:#f8f8f8;}
   .info_header{position:absolute;height:50px;line-height: 50px;border-top-left-radius:5px;border-top-right-radius:5px;z-index: 999;width: 100%;}
   .content_bottom{height: 22px;line-height:22px;background-color:#f8f8f8;}
   .btn span{width:100%;color:white;height:45px;line-height:45px; padding:10px;letter-spacing:3px;}
   .info_header_left{float:left;padding-left:20px;font-size:20px;width:70%;}
   .info_header_right{float:right;padding-right:10px;text-decoration: underline;}
   .info_header_left span{vertical-align: middle;margin-left: 10px;}
   .border-bottom-color-green{border-bottom: 4px solid #189c3a;}
   .border-bottom-color-blue{border-bottom: 4px solid #1b709f;}
   .border-bottom-color-yellow{border-bottom: 4px solid #cb6920;}
   .border-bottom-color-red{border-bottom: 4px solid #ac3d4a;}
   .info_content{margin:10px auto;;background-color:white;padding-bottom:10px;border-bottom-left-radius:5px;border-bottom-right-radius:5px;display:block;}
   .info_content .ele{height: 30px;width:90%;line-height: 30px;margin:0 auto;}
   .ele .left{width:40%;float:left;color:#707070}
   .ele .right{width:60%;float:left;color:#707070}
   .ele img{width: 20px;height: 20px;vertical-align:middle;}
   .repair_btn{position: absolute;float:right;right:15px;top:0px;}
   .pop{position: absolute;float:right;right:50px;top:-21px;font-size: 25px;}
   .info_middle{height:50px;}
   .repair_btn img{width: 20px;height: 15px;vertical-align:middle;}
   .card {margin: 10px auto;position: relative;height: 159px;text-align: left;width: 267px;}
	.cardbg {height: 159px;width: 267px;position: absolute;border-radius: 8px;-webkit-border-radius: 8px;-moz-border-radius: 8px;box-shadow: 0 0 4px rgba(0, 0, 0, 0.6);-moz-box-shadow: 0 0 4px rgba(0, 0, 0, 0.6);-webkit-box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);top: 0;left: 0;z-index: 1;}
	.card h1 {position: absolute;right: 10px;top: 7px;text-shadow: 0 1px rgba(255, 255, 255, 0.2);color: #000000;font-size: 11px;line-height: 25px;text-align: right;font-weight: normal;z-index: 2;}
	.card .verify {display: inline-block;height: 40px;top: 105px;right: 12px;text-align: right;line-height: 24px;color: #000000;font-size: 15px;text-shadow: 0 1px rgba(255, 255, 255, 0.2);z-index: 2;}
	.pdo em{display: block;line-height: 13px;font-size: 10px;font-weight: normal;font-style: normal;}
</style>


</head>
<!-- Loading Screen -->
<div id='loading' class='loadingPop'style="display: none;"><img src='./images/loading.gif' style="width:40px;"/><p class=""></p></div>

<body data-ctrl=true style="background:#f8f8f8;">
	<!--<header data-am-widget="header" class="am-header am-header-default">
		<div class="am-header-left am-header-nav" onclick="goBack();">
			<img class="am-header-icon-custom" src="./images/center/nav_bar_back.png" style="vertical-align:middle;"/><span style="margin-left:5px;">返回</span>
		</div>
	    <h1 class="am-header-title" style="font-size:18px;">会员卡管理</h1>
	</header>-->
	<div class="cardList"></div>
</body>		
<!--引入侧边栏 start-->
<?php  include_once('float.php');?>
<!--引入侧边栏 end-->
<script type="text/javascript">
var user_id = '<?php echo passport_encrypt((string)$user_id); ?>';
var customer_id = '<?php echo $customer_id; ?>';
var customer_id_en = '<?php echo $customer_id_en; ?>';
cardList();
function cardList(){
	 $.ajax({
			url:'vip_card.class.php',
			dataType: 'json',
			type: "post",
			data:{
			  'user_id':user_id,
			  'customer_id':customer_id_en
			},
			success:function(res){
				var content = "";
				for(id in res){
					content += "<div class='card' onclick='show_card("+res[id]['card_id']+")' >";
					content += '	<img class="cardbg" src="'+res[id]['imgurl']+'">';
					content += '	<h1 style="COLOR: #'+res[id]['font_color']+'">'+res[id]['shop_name']+''+res[id]['card_type']+'</h1>';
					content += '	<strong style="COLOR: #'+res[id]['num_color']+'" class="pdo verify">';
					content += '	<span id="cdnb">';
					content += '	<em>'+res[id]['card_type']+'编号</em>';
					if(res[id]['is_show_shopnum']==1){
						content += ''+res[id]['shop_card_number']+'';
					}else{
						content += ''+res[id]['card_number']+'';
					}
					content += '</span> </strong>';
					content += '</div> ';
				}
				$('.cardList').html(content);
			}

	});
}
function show_card(card_id){
	location.href = "../card/show_card.php?customer_id="+customer_id_en+"&user_id="+user_id+"&card_id="+card_id;
}
</script>
<?php require('../common/share.php'); ?>
</html>