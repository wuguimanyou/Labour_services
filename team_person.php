<?php

header("Content-type: text/html; charset=utf-8");     
require('../config.php');
require('../customer_id_decrypt.php'); //导入文件,获取customer_id_en[加密的customer_id]以及customer_id[已解密]
require('../common/utility_fun.php'); 
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD); 
mysql_select_db(DB_NAME) or die('Could not select database');
mysql_query("SET NAMES UTF8");
require('select_skin.php');
//头文件----start
require('../common/common_from.php');
//头文件----end

require('../common/jssdk.php');
$persion_id  = -1;//查询成员ID
$parent_name = "";
if(!empty($_POST['persion_id'])){
	$persion_id = $configutil->splash_new($_POST['persion_id']);
}


require('../common/own_data.php');
$info = new my_data();//own_data.php my_data类
$member = $info->team_member($persion_id,$customer_id);//调用团队成员资料方法

/*** 团队个人信息显示开关 start ***/
$is_phone 	   = 1;		//是否显示电话号码：0不显示，1显示
$is_qq 		   = 1;		//是否显示qq：0不显示，1显示
$is_weixin 	   = 1;		//是否显示微信号：0不显示，1显示
$is_weixincode = 1;		//是否显示微信二维码：0不显示，1显示
$query_pidt = "select is_phone,is_qq,is_weixin,is_weixincode from personal_info_display_t where isvalid=true and customer_id=".$customer_id." limit 1";
$result_pidt = mysql_query($query_pidt) or die('query_pidt failed:'.mysql_error());
while($row_pidt = mysql_fetch_object($result_pidt)){
	$is_phone 	   = $row_pidt->is_phone;
	$is_qq 		   = $row_pidt->is_qq;
	$is_weixin 	   = $row_pidt->is_weixin;
	$is_weixincode = $row_pidt->is_weixincode;
}
/*** 团队个人信息显示开关 end ***/
?>

<!DOCTYPE html>
<html>
<head>
    <title>我的团队</title>
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
    
    <link type="text/css" rel="stylesheet" href="./assets/css/amazeui.min.css" />
    <link type="text/css" rel="stylesheet" href="./css/css_<?php echo $skin ?>.css" /> 
    <script type="text/javascript" src="./assets/js/jquery.min.js"></script>    
    <script type="text/javascript" src="./assets/js/amazeui.js"></script>
    <script type="text/javascript" src="js/global.js"></script>
    <script type="text/javascript" src="./js/loading.js"></script>
    <script src="./js/jquery.ellipsis.js"></script>
    <script src="./js/jquery.ellipsis.unobtrusive.js"></script>
    <!-- 模板 -->
    
    
    <!-- 页联系style-->
    <link type="text/css" rel="stylesheet" href="./css/vic.css" />
    <link type="text/css" rel="stylesheet" href="./css/goods/team_person.css" />
    <!-- 页联系style-->
    
    
    
</head>

<!-- Loading Screen -->
<div id='loading' class='loadingPop'style="display: none;"><img src='./images/loading.gif' style="width:40px;"/><p class=""></p></div>
<!-- Loading Screen -->



<body data-ctrl=true style="background:#f8f8f8;">
	<!-- header部门-->
	<!-- <header data-am-widget="header" class="am-header am-header-default header">
		<div class="am-header-left am-header-nav"  onclick="goBack();">
			<img class="am-header-icon-custom header-img"  src="./images/center/nav_bar_back.png"/><span class = "header-span">返回</span>
		</div>
	    <h1 class="header-title ">我的团队</h1>
	    <div class="am-header-right am-header-nav"></div>
	</header>
	<div class="topDiv" style="height:49px;"></div> -->   <!-- 暂时屏蔽头部 -->
	<!-- header部门-->
	<!-- content -->
    <div class = "content" id="containerDiv">
    	<!-- content-header -->
    	<div class = "content-header">
    		<div class = "content-header-left1">
    			<img class = "content-header-left1-img" id = "itemWrapper-main-left1-img" src = "<?php echo $member['weixin_headimgurl']; ?>" width = "80" height = "80">
    		</div>
    		<div class = "content-header-left2" >
    			<div class =  "content-header-left2-row1">
    				<span class =  "content-header-left2-row1-span1"><?php echo $member['weixin_name']; ?></span>
					<?php if($member['p_id']>0){ ?>
						<span class = "content-header-left2-row1-span2 content-header-left2-row1-span2-juese-<?php echo $member['ident_num']; ?>" ><?php echo $member['ident']; ?></span>
					<?php } ?>
    			</div>
				<?php if($member['parent_name']!=""){ ?>
    			<div class = "content-header-left2-row2">
					<span>推荐人: <font><?php echo $member['parent_name']; ?></font></span>
				</div>
				<?php } ?>
    		</div>
			<?php if($member['phone']!="" and $is_phone==1){ ?>
    		<div  class = "content-header-right">
    			<a href="tel:<?php echo $member['phone']; ?>"><img class = "content-header-right-img" src = "./images/goods_image/20160050504-orange.png" width = "40" height = "40"></a>
    		</div>
			<?php }?>
    	</div>
    	<!-- content-header -->
    	<!-- content-row1 -->
    	<div class = "content-row1">
    		<div class = "content-row1-left">
	    		<img src = "./images/goods_image/20160050505.png"  width = "20" height = "20">
	    		<span>消费总额</span>
	    	</div>
	    	<div class = "content-row1-right" style = "">
	    		<span>￥<font></font><?php echo cut_num($member['total_money'],2);?></span>
	    	</div>
    	</div>
    	<!-- content-row1 -->
    	
    	<!-- content-subtitle -->
    	<div class = "content-subtitle">
    		<span>基本信息</span>
    	</div>
    	<!-- content-subtitle -->
    	
    	<!--content-main  -->
    	<div class = "content-main">
    		<div class = "content-main-row">
    			<div class = "content-main-row-left">
    				<span>姓名:</span>
    			</div>
    			<div class = "content-main-row-right">
    				<span><?php  if($member['name']==''){echo "未填写";}else{echo $member['name'];} ?></span>
    			</div>
    		</div>
    		<div class = "content-main-row">
    			<div class = "content-main-row-left">
    				<span>性别:</span>
    			</div>
    			<div class = "content-main-row-right" >
    				<?php if(1==$member['sex']){?><img class = "content-main-row-right-img-nan" src ="./images/goods_image/20160050506.png" width = "15" height = "15">
					<?php }elseif(2==$member['sex']){?> <img class = "content-main-row-right-img-nan" src ="./images/goods_image/female.png" width = "15" height = "15"><?php } ?>
    				<span class = "content-main-row-right-span"><?php if(1==$member['sex']){echo "男";}elseif(2==$member['sex']){echo "女";}else{echo "不明";} ?></span>
    			</div>
    		</div>
			<?php if($is_weixin==1){?>
    		<div class = "content-main-row">
    			<div class = "content-main-row-left">
    				<span>微信号:</span>
    			</div>
    			<div class = "content-main-row-right">
    				<span style = "color:grey;"><?php echo $member['wechat_id']; ?></span>
    			</div>
    		</div>
			<?php }?>
			<?php if($is_qq==1){?>
			<div class = "content-main-row">
    			<div class = "content-main-row-left">
    				<span>QQ:</span>
    			</div>
    			<div class = "content-main-row-right">
    				<span style = "color:grey;"><?php echo $member['qq']; ?></span>
    			</div>
    		</div>
			<?php }?>
    		<div class = "content-main-row">
    			<div class = "content-main-row-left">
    				<span>生日:</span>
    			</div>
    			<div class = "content-main-row-right">
    				<span style = "color:grey;"><?php echo $member['birthday']; ?></span>
    			</div>
    		</div>
    		<div class = "content-main-row">
    			<div class = "content-main-row-left">
    				<span>地区:</span>
    			</div>
    			<div class = "content-main-row-right">
    				<?php if($member['province'] != ""|| $member['city'] != ""){ ?><img class = "content-main-row-right-img-diqu"  src = "./images/goods_image/20160050507.png"  width = "13" height = "15"><?php } ?>
    				<span class = "content-main-row-right-span" ><?php echo $member['province']; ?><font class = "content-main-row-right-img-diqu-cell1"> <?php if($member['province'] == "" && $member['city'] ==  ""){echo "未填写";}else{echo $member['city'];} ?></font></span>
    			</div>
    		</div>
			<?php if($is_weixincode==1){?>
			<div class = "content-main-row" style="height: 70px;">
    			<div class = "content-main-row-left" style="line-height: 70px;">
    				<span>微信二维码:</span>
    			</div>
    			<div class = "content-main-row-right" style="line-height: 70px;">
    				<img class = "content-main-row-right-img-qrcode"  src = "<?php echo $member['wechat_code'];?>"  width = "60" height = "60">
    			</div>
    		</div>
			<?php }?>
    		<div class = "content-main-row-last">
    			<div class = "content-main-row-left">
    				<span>职业:</span>
    			</div>
    			<div class = "content-main-row-right">
    				<span style = "color:grey;"><?php  if($member['occupation']==''){ echo "未填写";}else{ echo $member['occupation'];}?></span>
    			</div>
    		</div>
    	</div>
    	<!--content-main  -->
	</div>
	<!-- content --->
	<!--引入侧边栏 start-->
<?php  include_once('float.php');?>
<!--引入侧边栏 end-->
<?php require('../common/share.php'); ?>
	
<!-- 页联系js -->
<script src="./js/goods/team_person.js"></script>
</body>
</html>