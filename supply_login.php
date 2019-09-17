<?php
header("Content-type: text/html; charset=utf-8"); 
require('../config.php');
require('../customer_id_decrypt.php'); //导入文件,获取customer_id_en[加密的customer_id]以及customer_id[已解密]
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD);
mysql_select_db(DB_NAME) or die('Could not select database');
require('../proxy_info.php');
mysql_query("SET NAMES UTF8");
/*require('../common/jssdk.php');
$jssdk = new JSSDK($customer_id);
$signPackage = $jssdk->GetSignPackage();*/
//头文件----start
require('../common/common_from.php');
//头文件----end
require('select_skin.php');
$query="select id,isAgent,status from promoters where isvalid=true and user_id=".$user_id;
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
$promoter_id = -1;
$isAgent	 = -1;
$status		 = -1;
while ($row = mysql_fetch_object($result)) {
    $promoter_id = $row->id;
	$isAgent 	 = $row->isAgent;	//判断 0为推广员 1为代理商 2:顶级推广员 3:供应商
	$status 	 = $row->status;	//判断是否为推广员
	break;
}

$sp_status = 0;
$is_supply = -1;
$query="select id,status from weixin_commonshop_applysupplys where isvalid=true and user_id=".$user_id;
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
while ($row = mysql_fetch_object($result)) {
   $is_supply = $row->id;		//判断是否已经提交过申请;
   $sp_status = $row->status;	//判断申请状态
   break;
}

$query="select id from weixin_commonshop_applyagents where isvalid=true and status=0 and user_id=".$user_id;
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
$is_apply = -1;
while ($row = mysql_fetch_object($result)) {
   $is_apply = $row->id;	//判断是否已经提交过代理商申请;
   break;
}

$query="select name,exp_name from weixin_commonshops where isvalid=true and customer_id=".$customer_id;
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
$shop_name = "商城";
$exp_name  = "推广员";
while ($row = mysql_fetch_object($result)) {
    $shop_name = $row->name;
    $exp_name  = $row->exp_name;
	break;
}


$query = "select id,supply_detail from weixin_commonshop_supplys where isvalid=true and customer_id=".$customer_id;
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
$supply_detail = "";	//供应商详情
$supply_id 	   = -1;
while ($row = mysql_fetch_object($result)) {
	$supply_id	   = $row->id;
	$supply_detail = $row->supply_detail;
}
//是否申请过区域代理
$query_team = "select id from weixin_commonshop_team_aplay where isvalid=true and status=0 and customer_id=".$customer_id." and aplay_user_id=".$user_id;
$result_team = mysql_query($query_team) or die('query_team failed'.mysql_error());
$team_id = -1;
while($row_team = mysql_fetch_object($result_team)){
	$team_id = $row_team->id;
}

?>
<!DOCTYPE html>
<html lang="zh-CN"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>供应商申请</title>
		<meta charset="utf-8">
		<meta content="" name="description">
		<meta content="" name="keywords">
		<meta content="eric.wu" name="author">
		<meta content="telephone=no, address=no" name="format-detection">
		<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
		<link href="../weixin_inter/agent_login/css/reset.css" rel="stylesheet">
		<link href="../weixin_inter/agent_login/css/common.css" rel="stylesheet">
		<link href="../weixin_inter/agent_login/css/register.css" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="./css/order_css/global.css" />
		<link type="text/css" rel="stylesheet" href="./css/css_<?php echo $skin ?>.css" /> 
		<script type="text/javascript" src="../common/js/zepto.min.js"></script>
		<script type="text/javascript" src="./js/global.js"></script>
		<script type="text/javascript" src="../common_shop/common/js/hidetool.js"></script>
		<script type="text/javascript" src="./assets/js/jquery.min.js"></script>  
		<script type="text/javascript" src="../common/utility.js"></script>
		<style>
		.spanleft{
			float: left;
			width: 8%;
			font-size: 14px;
			line-height: 40px;
			height: 40px;
		}
		</style>
	</head>
	<body onselectstart="return true;" ondragstart="return false;" onload="initload();">
		<div data-role="container" class="container register">
			<div class="body">
				<header data-role="header">
					<img src="../weixin_inter/agent_login/images/supply.jpg">
				</header>
				<form id="frmLogin" action="#"  method="post" style="margin-top:20px;display:block;">
				<section data-role="body" class="body">
					<div class="register_info" style="display: block;">
					<?php
						if($is_supply>0 and $sp_status == 0){
					?>
						<a class="btn">申请中...</a>		
					<?php
						}else if($isAgent==3){
					?>
						<a class="btn">您已成为供应商</a>	
					<?php
						}else{
					?>
						<a class="btn btn_apply" id="submit" onclick="apply()">申请成为供应商</a>
					<?php
						}
					?>
					<a class="btn btn_ing" style="display:none;">申请中...</a>
					</div>
					<ul class="desc">
						<li>						
							<span class="title">供应商说明：</span>
							<label><?php echo $supply_detail;?></label>
						</li>
					</ul>
				</section>
				</form>
				
				<footer data-role="footer">                                    
                    <div data-role="copyright" data-copyright="copyright1" class="copyright1">
						<div class="widget_wrap">
							<ul class="tbox">
								<li>
									<p>
										<a href="javascript:;">©<?php echo $shop_name;?></a>
									</p>
								</li>
							</ul>
						</div>
					</div>
					
				</footer>
			</div>
		</div>
	<script>
		var is_supply 	   = '<?php echo $is_supply;?>';
		var is_apply 	   = '<?php echo $is_apply;?>';
		var supply_id 	   = '<?php echo $supply_id;?>'; 	//商家是否设置了供应商设置
		var customer_id    = '<?php echo $customer_id;?>';
		var customer_id_en = '<?php echo $customer_id_en;?>';
		var status 		   = '<?php echo $status;?>';
		var sp_status      = '<?php echo $sp_status;?>';
		var isAgent 	   = '<?php echo $isAgent;?>';
		var team_id 	   = '<?php echo $team_id;?>';
		var submitcount    = 0;
		
		function apply(){
			if(isAgent==1){ 
				showAlertMsg ("提示：","您已成为代理商,请勿申请","知道了");
				return;
			}
			if(isAgent==3){ 
				showAlertMsg ("提示：","您已成为供应商,请勿申请","知道了");
				return;
			}
			if(isAgent>=5){
				showAlertMsg ("提示：","您已成为区域代理,请勿申请","知道了");
				return;
			}
			if(is_apply>0){
				showAlertMsg ("提示：","您已经提交过代理商申请","知道了");
				return;
			}
			if(team_id>0){
				showAlertMsg ("提示：","您已经提交过区域代理申请","知道了");
				return;
			}
			if(supply_id<0){
				showAlertMsg ("提示：","商家还没设置,请联系商家","知道了");
				return;
			}
			if(submitOnce()){ 
				if(status != 1){
					showAlertMsg ("提示：","您还不是<?php echo $exp_name;?>","知道了");
					return;
				}
				if(is_supply > 0){
					if(sp_status == -1){
						showAlertMsg ("提示：","商家已经拒绝您的申请,请联系商家","知道了");
						return;
					}else if(sp_status==0){
						showAlertMsg ("提示：","申请已提交,等待商家审核","知道了");
						return;
					}else if(sp_status==1){
						showAlertMsg ("提示：","商家已经通过了","知道了");
						return;
					}
				}
				$('.btn_apply').hide();
				$('.btn_ing').show();
					$.ajax({
						url: 'save_supplylogin.php?customer_id='+customer_id_en,
						data:{
							
						},
						type: 'post',
						dataType: 'json',
						async: true,
						success:function(res){
							if(1==res.status){
								showConfirmMsg('提交申请成功','返回我的特权还是继续操作？','返回我的特权','继续操作',function(){
									window.location.href = 'my_privilege.php?customer_id='+customer_id_en;
								})
							}
						},
						error:function(er){
							
						}
					});
			}
		}
		
		function submitOnce(){
			if (submitcount == 0){
			   submitcount++;
			   return true;
			} else{
			   showAlertMsg ("提示：","正在操作，请不要重复操作！","知道了");
			   return false;
			}
		}
		
		function initload(){

		   if(sp_status==-1){
				showAlertMsg ("提示：","商家已经拒绝您的申请,请联系商家","知道了");
				return;
			}
			
		}

	</script>
<!--引入微信分享文件----start-->
<script>
<!--微信分享页面参数----start-->
debug=false;
share_url=''; //分享链接
title=""; //标题
desc=""; //分享内容
imgUrl="";//分享LOGO
share_type=3;//自定义类型
<!--微信分享页面参数----end-->
</script>
<?php require('../common/share.php');?>
<!--引入微信分享文件----end-->
<!--引入侧边栏 start-->
<?php  include_once('float.php');?>
<!--引入侧边栏 end-->
</body>
</html>