<?php
header("Content-type: text/html; charset=utf-8");     
require('../config.php');
require('../customer_id_decrypt.php'); //导入文件,获取customer_id_en[加密的customer_id]以及customer_id[已解密]
//require('../back_init.php'); 
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD); 
mysql_select_db(DB_NAME) or die('Could not select database');
mysql_query("SET NAMES UTF8");
require('../common/jssdk.php');
$customer_id = 3243;
$persion_id  = -1;
$parent_name = "";
if(!empty($_GET['persion_id'])){
	$persion_id = $_GET['persion_id'];
}
if(!empty($_GET['parent_name'])){
	$parent_name = $_GET['parent_name'];
}

 $query = "SELECT u.weixin_headimgurl,u.weixin_name,u.name,u.sex,u.qq,u.birthday,u.province,u.city,p.isAgent,p.is_consume from weixin_users u left join promoters p on u.id=p.user_id where u.isvalid=true and p.isvalid=true and u.id=".$persion_id." and u.customer_id = ".$customer_id;
 //echo $query;
 $result = mysql_query($query) or die('Query failed1: ' . mysql_error());
 	$name              = "";
	$sex               = "";
	$qq                = "";
	$birthday          = "";
	$weixin_name       = "";
	$weixin_headimgurl = "";
	$isAgent           = 0;
	$is_consume        = -1;
	$ident_num         = 0;
	$ident             = "";
	while ($row = mysql_fetch_object($result)) {
		$name              = $row->name;
		$sex               = $row->sex;
		$qq                = $row->qq;
		$weixin_headimgurl = $row->weixin_headimgurl;
		$weixin_name       = $row->weixin_name;
		$birthday          = $row->birthday;
		$province          = $row->province;
		$isAgent           = $row->isAgent;
		$is_consume        = $row->is_consume;
	}
		if($is_consume>0){ 
			$ident_num = 1;
			if(1==$is_consume){
				$ident = "代理";
			}elseif(2==$is_consume){
				$ident = "渠道";
			}elseif(3==$is_consume){
				$ident = "总经理";
			}elseif(4==$is_consume){
				$ident = "股东";
			}
		}elseif(5==$isAgent||6==$isAgent||7==$isAgent||8==$isAgent){
			$ident_num = 0;
			$is_showcustomer = -1;	
			$a_customer      = "";	
			$c_customer      = "";	
			$p_customer      = "";	
			$is_diy_area     = -1;	
			$diy_customer    = "";						
			$query1 = "select is_showcustomer,a_customer,c_customer,p_customer,is_diy_area,diy_customer from weixin_commonshop_team where isvalid=true and customer_id=".$customer_id;
			$result2 = mysql_query($query1) or die('Query failed2: ' . mysql_error());
			while ($row1 = mysql_fetch_object($result2)) {
				$is_showcustomer = $row1->is_showcustomer;
				$a_customer      = $row1->a_customer;
				$c_customer      = $row1->c_customer;
				$p_customer      = $row1->p_customer;
				$is_diy_area     = $row1->is_diy_area;
				$diy_customer    = $row1->diy_customer;
			}
			 if(0==$is_showcustomer){
				 $ident = "区代";
			}elseif(5==$isAgent){
				 $ident = $a_customer;
			}elseif(6==$isAgent){
				 $ident = $c_customer;
			}elseif(7==$isAgent){
				 $ident = $p_customer;
			}elseif(8==$isAgent&&1==$is_diy_area){
				 $ident = $diy_customer;
			}elseif(8==$isAgent&&0==$is_diy_area){
				 $ident = "区代";
			}
		}elseif(0==$isAgent){
			 $ident = "推广员";
			 $ident_num = 2;
		}elseif(1==$isAgent){
			 $ident = "代理商";
			 $ident_num = 5;
		}elseif(3==$isAgent){
			 $ident = "供应商";
			 $ident_num = 4;
		}elseif(4==$isAgent){
			 $ident = "技师";
			 $ident_num = 3;
		}
	if($qq == ""){
		$qq = "未填写";
	}
	if($birthday == ""){
		$birthday = "未填写";
	}
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
    <link type="text/css" rel="stylesheet" href="./css/css_orange.css" />

    <script type="text/javascript" src="./assets/js/jquery.min.js"></script>    
    <script type="text/javascript" src="./assets/js/amazeui.js"></script>
    <script type="text/javascript" src="./js/global.js"></script>
    <script type="text/javascript" src="./js/loading.js"></script>
    <script src="./js/jquery.ellipsis.js"></script>
    <script src="./js/jquery.ellipsis.unobtrusive.js"></script>
    <!-- 模板 -->
    
    
    <!-- 页联系style-->
    <link type="text/css" rel="stylesheet" href="./css/vic.css" />
    <link type="text/css" rel="stylesheet" href="./css/goods/wodetuandui2.css" />
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
	</header> --><!-- 暂时隐藏头部导航栏 -->
	<!-- header部门-->
	<!-- content -->
    <div class = "content" id="containerDiv">
    	<!-- content-header -->
    	<div class = "content-header">
    		<div class = "content-header-left1">
    			<img class = "content-header-left1-img" id = "itemWrapper-main-left1-img" src  "<?php echo $weixin_headimgurl; ?>" width = "80" height = "80">
    		</div>
    		<div class = "content-header-left2" >
    			<div class =  "content-header-left2-row1">
    				<span class =  "content-header-left2-row1-span1"><?php echo $weixin_name; ?></span>
					<span class = "content-header-left2-row1-span2 content-header-left2-row1-span2-juese-<?php echo $ident_num; ?>" ><?php echo $ident; ?></span>
    			</div>
    			<div class = "content-header-left2-row2">
					<span>推荐人: <font><?php echo $parent_name; ?></font></span>
				</div>
    		</div>
    		<div  class = "content-header-right">
    			<a href="tel:13580795363"><img class = "content-header-right-img" src = "./images/goods_image/20160050504-orange.png" width = "40" height = "40"></a>
    		</div>
    	</div>
    	<!-- content-header -->
    	<!-- content-row1 -->
    	<div class = "content-row1">
    		<div class = "content-row1-left">
	    		<img src = "./images/goods_image/20160050505.png"  width = "20" height = "20">
	    		<span>消费总额</span>
	    	</div>
	    	<div class = "content-row1-right" style = "">
	    		<span>￥<font></font>122.225</span>
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
    				<span><?php echo $name; ?></span>
    			</div>
    		</div>
    		<div class = "content-main-row">
    			<div class = "content-main-row-left">
    				<span>性别:</span>
    			</div>
    			<div class = "content-main-row-right" >
    				<?php if(1==$sex){?><img class = "content-main-row-right-img-nan" src ="./images/goods_image/20160050506.png" width = "15" height = "15">
					<?php }elseif(2==$sex){?> <img class = "content-main-row-right-img-nan" src ="./images/goods_image/female.png" width = "15" height = "15"><?php } ?>
    				<span class = "content-main-row-right-span"><?php if(1==$sex){echo "男";}elseif(2==$sex){echo "女";}else{echo "不明";} ?></span>
    			</div>
    		</div>
    		<div class = "content-main-row">
    			<div class = "content-main-row-left">
    				<span>QQ:</span>
    			</div>
    			<div class = "content-main-row-right">
    				<span style = "color:grey;"><?php echo $qq; ?></span>
    			</div>
    		</div>
    		<div class = "content-main-row">
    			<div class = "content-main-row-left">
    				<span>生日:</span>
    			</div>
    			<div class = "content-main-row-right">
    				<span style = "color:grey;"><?php echo $birthday; ?></span>
    			</div>
    		</div>
    		<div class = "content-main-row">
    			<div class = "content-main-row-left">
    				<span>地区:</span>
    			</div>
    			<div class = "content-main-row-right">
    				<?php if($province != ""|| $city != ""){ ?><img class = "content-main-row-right-img-diqu"  src = "./images/goods_image/20160050507.png"  width = "13" height = "15"><?php } ?>
    				<span class = "content-main-row-right-span" ><?php echo $province; ?><font class = "content-main-row-right-img-diqu-cell1"> <?php if($province == "" && $city ==  ""){echo "未填写";}else{echo $city;} ?></font></span>
    			</div>
    		</div>
    		<div class = "content-main-row-last">
    			<div class = "content-main-row-left">
    				<span>职业:</span>
    			</div>
    			<div class = "content-main-row-right">
    				<span style = "color:grey;">程序猿</span>
    			</div>
    		</div>
    	</div>
    	<!--content-main  -->
	</div>
	<!-- content --->
</body>		
<!-- 页联系js -->
<script src="/js/goods/global.js"></script>
<script src="./js/goods/wodetuandui2.js"></script>
</body>
</html>