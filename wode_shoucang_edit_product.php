<?php
header("Content-type: text/html; charset=utf-8"); 
require('../config.php');
require('../customer_id_decrypt.php');
require('../common/utility.php');
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD);
mysql_select_db(DB_NAME) or die('Could not select database');
require('../common/common_from.php');

$CF = new CheckFrom();
$CF->isFrom($customer_id);	
//$customer_id = 3243;	
$from_type = -1;	
$is_bind = -1;	
$user_id = -1;	
$from_type = $_SESSION["from_type_".$customer_id];	//从哪里进来 0:网页 1:微信 2:APP 3:支付宝
$user_id = $_SESSION["user_id_".$customer_id]; 

$user_id = 194515 ;

$ids = $configutil->splash_new($_POST["ids"]);

//var_dump($ids);
$collect_ids = explode(',',$ids);
//var_dump($collect_ids);

	
?>
<!DOCTYPE html>
<html>
<head>
    <title>编辑</title>
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
    <link type="text/css" rel="stylesheet" href="./css/goods/global.css" />
    <link type="text/css" rel="stylesheet" href="./css/goods/wode_shoucang_bianji1.css" />
    
    <!-- 页联系style-->
    
    
    
</head>

<body data-ctrl=true>
	<!-- header部门-->
	<!-- <header data-am-widget="header" class="am-header am-header-default header" style = "z-index:102; position : fixed; top:0px;background:#1d1e20;">
		<div class="am-header-left am-header-nav header-btn">
			<img class="am-header-icon-custom"  src="./images/center/nav_bar_back.png"/><span>返回</span>
		</div>
	    <h1 class="header-title">编辑</h1>
	    <div class="am-header-right am-header-nav">
		</div>
	</header>
	<div class="topDiv" style="width:100%;height:49px;"></div> --><!-- 暂时隐藏头部导航栏 -->
	<!-- header部门-->
	
	<div class = "container-title" >
		<span style="color:#676767;">共收藏<font class = "font-red">4</font>个商品</span>
	</div>
    <div class="containerWrapper">
    	<ul class= "list-wrapper">
		<?php 
		/*$zekou = 0;
		$cashback_money = 0;
		$pro_name 			= '';//产品名称
		$default_imgurl 	= '';//默认图片
		$pro_supply_id 		= '';//供应商ID
		$orgin_price 		= '';//原价
		$now_price 			= '';//现价
		$isvp 				= '';//是否VP产品
		$vp_score 			= '';//VP值
		$cashback 			= '';//返现金额
		$cashback_r 		= '';//返现比例
		$show_sell_count	= '';//虚拟销售量
		$is_free_shipping	= '';//是否包邮，1是，0否
		foreach($collect_ids as $k => $values){
				$collect_id = $values;
				$query2 = "select name,default_imgurl,is_supply_id,orgin_price,now_price,isvp,vp_score,cashback,cashback_r,show_sell_count,is_free_shipping from weixin_commonshop_products where isvalid=true and isout=0 and customer_id=".$customer_id." and id=".$collect_id."";
				echo $query2;		
				$result2=mysql_query($query2)or die('L41 Query failed'.mysql_error());
				while($row2=mysql_fetch_object($result2)){
					$pro_name		  = $row2->name;
					$default_imgurl   = $row2->default_imgurl;
					$pro_supply_id 	  = $row2->is_supply_id;
					$orgin_price 	  = $row2->orgin_price;
					$now_price 		  = $row2->now_price;
					$isvp 			  = $row2->isvp;
					$vp_score 		  = $row2->vp_score;
					$cashback 		  = $row2->cashback;
					$cashback_r		  = $row2->cashback_r;
					$show_sell_count  = $row2->show_sell_count;
					$is_free_shipping = $row2->is_free_shipping;
				}*/

		
		?>
    		<li class = "li-wrapper">
    			<div class = "item-wrapper">
    				<div class = "item-left1" >
    					<img class = "item-select" src="./images/list_image/checkbox_off.png" width="20" height="20"/>
    				</div>
    				<div class = "item-wrapper-left2">
    					<img class = "item-photo" src = "<?php echo $default_imgurl ;?>" width="100" height = "100"/>
    				</div>
    				<div class = "item-wrapper-right">
    					<div class = "item-wrapper-right-row1">
							<?php 
							if($pro_supply_id>0){
							?>
    						<span class = "item-wrapper-right-row1-span1">品牌</span>
							<?php } ?>
    						<span class = "item-wrapper-right-row1-span2"><?php echo $pro_name ;?></span>
    					</div>
    					<div class = "item-wrapper-right-row2">
    						<span class = "item-wrapper-right-row2-span1">￥<?php echo $now_price ;?></span>       		
    						<span class = "item-wrapper-right-row2-span2"><?php echo $orgin_price ;?></span>    
    					</div>
    					<div class = "item-wrapper-right-row3">
							<?php 
								$zekou = round($now_price/$orgin_price,2);
								if($zekou>0){
							?>
    						<span type="text" class="am-btn am-btn-danger am-radius item-wrapper-right-row3-span1"><?php echo  $zekou;?>折</span>
							<?php }
							
							if($isvp>0){
								
							?>							
   						<span type="text" class="am-btn am-btn-secondary am-radius item-wrapper-right-row3-span2">VP:<?php echo $$vp_score;?></span>
							<?php }
							if($cb_condition==0){
								$cashback_money = $cashback;
							}else{
								$cashback_money = $now_price * $cashback_r;
							}
							if($cashback>0){
							?>
    						<span type="text" class="am-btn am-btn-warning am-radius item-wrapper-right-row3-span3">返￥<?php echo $cashback_money?></span>
							<?php } ?>
    					</div>
    					<div class = "item-wrapper-right-row4">
						<?php 
						if($is_free_shipping>0){
						?>
						<span class = "item-wrapper-right-row4-span1">包邮</span>
						<?php } ?>       		
    						<span class = "item-wrapper-right-row4-span2">已销<?php echo $show_sell_count;?></span>    
    					</div>
    				</div>
    			</div>
    		</li>
		<?php //} ?>	
    	</ul>
	</div>
	<div class = "bottom-bar" style = "position: fixed;bottom: 0; height: 70px; width: 100%;background: white;border: 1px solid #d4d4d4;padding-left: 10px;line-height: 70px;">
		<div class = "bottom-bar-left1" style = "width: 20px;float: left;vertical-align: middle;line-height: 70px;">
			<img class="all-select" src="./images/list_image/checkbox_off.png" width="20p" height="20" style = "width: 20px;height: 20px;vertical-align: middle;"/>
		</div>
		<span class="bottom-bar-left1-span" style = "float: left; margin-left: 5px;">选择</span>
		<div class = "bottom-bar-right" style = "float: right;width: 120px;line-height: 70px; text-align: center;">
			<span class = "bottom-bar-button del-btn" style = "padding:10px 20px; color:white;">删除</span>
		</div>
    </div>
   
    
</body>		
<script>
//初始化
var config = {
	customer_id:'<?php echo $customer_id?>',
	customer_id_en:'<?php echo $customer_id_en?>',
	user_id:'<?php echo $user_id?>',
}
</script>
<!-- 页联系js -->
<script src="./js/goods/global.js"></script>
<script src="./js/goods/wode_shoucang_bianji1.js"></script>
</body>
</html>