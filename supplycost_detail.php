<?php
header("Content-type: text/html; charset=utf-8"); //test  sda

require('../config.php');
require('../customer_id_decrypt.php'); //导入文件,获取customer_id_en[加密的customer_id]以及customer_id[已解密]
require('../back_init.php');
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD);
mysql_select_db(DB_NAME) or die('Could not select database');

require('../proxy_info.php');

mysql_query("SET NAMES UTF8");

$user_id=-1;

if(!empty($_GET["user_id"])){
    $user_id = $configutil->splash_new($_GET["user_id"]);
}

$istype=1;

if(!empty($_GET["istype"])){
    $istype = $configutil->splash_new($_GET["istype"]);		//1:库存记录;2:进账记录
}

$query = 'SELECT id,appid,appsecret,access_token FROM weixin_menus where isvalid=true and customer_id='.$customer_id;
$result = mysql_query($query) or die('Query failed: ' . mysql_error());  
$access_token="";
while ($row = mysql_fetch_object($result)) {
	$keyid =  $row->id ;
	$appid =  $row->appid ;
	$appsecret = $row->appsecret;
	$access_token = $row->access_token;
	break;
}

//新增客户
$new_customer_count =0;
//今日销售
$today_totalprice=0;
//新增订单
$new_order_count =0;
//新增推广员
$new_qr_count =0;

$nowtime = time();
$year = date('Y',$nowtime);
$month = date('m',$nowtime);
$day = date('d',$nowtime);

$query="select count(1) as new_order_count from weixin_commonshop_orders where isvalid=true and customer_id=".$customer_id." and year(createtime)=".$year." and month(createtime)=".$month." and day(createtime)=".$day;
$result = mysql_query($query) or die('Query failed: ' . mysql_error());  
 //  echo $query;
while ($row = mysql_fetch_object($result)) {
   $new_order_count = $row->new_order_count;
   break;
}

$query="select sum(totalprice) as today_totalprice from weixin_commonshop_orders where paystatus=1 and sendstatus!=4 and isvalid=true and customer_id=".$customer_id." and year(createtime)=".$year." and month(createtime)=".$month." and day(createtime)=".$day;
$result = mysql_query($query) or die('Query failed: ' . mysql_error());  
 //  echo $query;
while ($row = mysql_fetch_object($result)) {
   $today_totalprice = $row->today_totalprice;
   break;
}
$today_totalprice = round($today_totalprice,2);

$query="select count(1) as new_customer_count from weixin_commonshop_customers where isvalid=true and customer_id=".$customer_id." and year(createtime)=".$year." and month(createtime)=".$month." and day(createtime)=".$day;
$result = mysql_query($query) or die('Query failed: ' . mysql_error());  
 //  echo $query;
while ($row = mysql_fetch_object($result)) {
   $new_customer_count = $row->new_customer_count;
   break;
}

$query="select count(1) as new_qr_count from promoters where isvalid=true and status=1 and customer_id=".$customer_id." and year(createtime)=".$year." and month(createtime)=".$month." and day(createtime)=".$day;
$result = mysql_query($query) or die('Query failed: ' . mysql_error());  
 //  echo $query;
while ($row = mysql_fetch_object($result)) {
   $new_qr_count = $row->new_qr_count;
   break;
}

$query2= "select name,weixin_name,phone from weixin_users where isvalid=true and id=".$user_id." and customer_id=".$customer_id; 
$result2 = mysql_query($query2) or die('Query failed: ' . mysql_error());
$username="";
$userphone="";
while ($row2 = mysql_fetch_object($result2)) {
	$username=$row2->name;
	$phone=$row2->phone;
	$weixin_name = $row2->weixin_name;
	$username = $username."(".$weixin_name.")";
	break;
}

$query2="select supply_money from weixin_commonshop_applysupplys where status=1 and isvalid=true and user_id=".$user_id;	
$supply_money = 0;
$result2 = mysql_query($query2) or die('Query failed: 1' . mysql_error());
while ($row2 = mysql_fetch_object($result2)) {
	$supply_money = $row2->supply_money;
}

 $search_batchcode="";
if(!empty($_POST["search_batchcode"])){
   $search_batchcode = $_POST["search_batchcode"];
}



$is_distribution=0;//渠道取消代理商功能
//代理模式,分销商城的功能项是 266
$query1="select cf.id,c.filename from customer_funs cf inner join columns c where c.isvalid=true and cf.isvalid=true and cf.customer_id=".$customer_id." and c.filename='scdl' and c.id=cf.column_id";
$result1 = mysql_query($query1) or die('Query failed: ' . mysql_error());  
$dcount= mysql_num_rows($result1);
if($dcount>0){
   $is_distribution=1;
}
$is_supplierstr=0;//渠道取消供应商功能
//供应商模式,渠道开通与不开通
$query1="select cf.id,c.filename from customer_funs cf inner join columns c where c.isvalid=true and cf.isvalid=true and cf.customer_id=".$customer_id." and c.filename='scgys' and c.id=cf.column_id";
$result1 = mysql_query($query1) or die('Query failed: ' . mysql_error());  
$dcount= mysql_num_rows($result1);
if($dcount>0){
   $is_supplierstr=1;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title></title>
<link href="css/global.css" rel="stylesheet" type="text/css">
<link href="css/main.css" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" rev="stylesheet" href="../css/icon.css" media="all">
<link href="css/shop.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../common/js/jquery-1.7.2.min.js"></script>
	
</head>

<body>

<style type="text/css">body, html{background:url(images/main-bg.jpg) left top fixed no-repeat;}</style>
<div class="div_line">
		   <div class="div_line_item" onclick="show_newOrder(<?php echo $customer_id_en; ?>);">
		      今日订单: <span style="padding-left:10px;font-size:18px;font-weight:bold"><?php echo $new_order_count; ?></span>
		   </div>
		   <div class="div_line_item_split"></div>
		   <div class="div_line_item"  onclick="show_todayMoney(<?php echo $customer_id_en; ?>);">
		      今日销售: <span style="padding-left:10px;color:red;font-size:18px;font-weight:bold">￥<?php echo $today_totalprice; ?></span>
		   </div>
		   <div class="div_line_item_split"></div>
		   <div class="div_line_item"  onclick="show_newCustomer(<?php echo $customer_id_en; ?>);">
		       新增客户: <span style="padding-left:10px;font-size:18px;font-weight:bold"><?php echo $new_customer_count; ?></span>
		   </div>
		   <div class="div_line_item_split"></div>
		   <div class="div_line_item"  onclick="show_newQrsell(<?php echo $customer_id_en; ?>);">
		      新增推广员: <span style="padding-left:10px;font-size:18px;font-weight:bold"><?php echo $new_qr_count; ?></span>
		   </div>
</div>
<div id="iframe_page">
	<div class="iframe_content">
	<div class="r_nav">
		<ul>
			<li id="auth_page0" class=""><a href="base.php?customer_id=<?php echo $customer_id_en; ?>">基本设置</a></li>
			<li id="auth_page1" class=""><a href="fengge.php?customer_id=<?php echo $customer_id_en; ?>">风格设置</a></li>
			<li id="auth_page2" class=""><a href="defaultset.php?customer_id=<?php echo $customer_id_en; ?>&default_set=1">首页设置</a></li>
			<li id="auth_page3" class=""><a href="product.php?customer_id=<?php echo $customer_id_en; ?>">产品管理</a></li>
			<li id="auth_page4" class=""><a href="order.php?customer_id=<?php echo $customer_id_en; ?>&status=-1">订单管理</a></li>
			<?php if($is_supplierstr){?><li id="auth_page5" class="cur"><a href="supply.php?customer_id=<?php echo $customer_id_en; ?>">供应商</a></li><?php }?>
			<?php if($is_distribution){?><li id="auth_page6" class=""><a href="agent.php?customer_id=<?php echo $customer_id_en; ?>">代理商</a></li><?php }?>
			<li id="auth_page7" class=""><a href="qrsell.php?customer_id=<?php echo $customer_id_en; ?>">推广员</a></li>
			<li id="auth_page8" class=""><a href="customers.php?customer_id=<?php echo $customer_id_en; ?>">顾客</a></li>
			<li id="auth_page9"><a href="shops.php?customer_id=<?php echo $customer_id_en; ?>">门店</a></li>
		</ul>
	</div>
<link href="css/operamasks-ui.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/operamasks-ui.min.js"></script>
<script type="text/javascript" src="../js/tis.js"></script>

	<div id="orders" class="r_con_wrap">
		<div class="search">
	     姓名：<span style="font-weight:bold"><?php echo $username; ?></span>&nbsp;&nbsp;&nbsp; 手机号：<span style="font-weight:bold"><?php echo $phone; ?></span>&nbsp;&nbsp;&nbsp;
		 <?php if($istype==3){?>
		  账目余额：<span style="font-weight:bold;font-size:22px;color:red"><?php echo $supply_money; ?>元</span>
		  <?php }?>
		</div> 
		
		<form class="search" id="search_form" method="post" action="supplycost_detail.php?customer_id=<?php echo $customer_id_en; ?>&user_id=<?php echo $user_id; ?>&istype=<?php echo $istype;?>">
			&nbsp;订单号:<input type=text name="search_batchcode" id="search_batchcode" value="<?php echo $search_batchcode; ?>" style="width:80px;" />
		   <input type="submit" class="search_btn" value="搜 索">
		</form>	
		
		<table border="0" cellpadding="5" cellspacing="0" class="r_con_table" id="order_list">
			<thead>
				<tr>
					<td width="3%" nowrap="nowrap">ID</td>
					<td width="13%" nowrap="nowrap">订单号</td>
					<td width="10%" nowrap="nowrap">账目记录</td>
					<td width="10%" nowrap="nowrap">每次结算余额</td>
					<td width="10%" nowrap="nowrap">时间</td>
					<td width="10%" nowrap="nowrap">消费说明</td>
				</tr>
			</thead>
			<tbody>
			   <?php 
				$pagenum = 1;

				if(!empty($_GET["pagenum"])){
				$pagenum = $configutil->splash_new($_GET["pagenum"]);
				}
				$pagesize=20;
				if(!empty($_GET["pagesize"])){
				$pagesize = $configutil->splash_new($_GET["pagesize"]);
				}
				if(!empty($_POST["pagesize"])){
				$pagesize = $configutil->splash_new($_POST["pagesize"]);
				}
				$start = ($pagenum-1) * $pagesize;
				$end = $pagesize;

				switch($istype){
						case 3:
						$query = "select id,batchcode,price,detail,type,createtime,after_inventory,after_getmoney,withdrawal_id from weixin_commonshop_agentfee_records where type in(5,6) and isvalid=true and  user_id=".$user_id;
						break;
				}
				// $query = "select id,batchcode,price,detail,type,createtime,after_inventory,after_getmoney from weixin_commonshop_agentfee_records where isvalid=true and  user_id=".$user_id;
				if(!empty($search_batchcode)){
				   
					$query = $query." and batchcode like '%".$search_batchcode."'";
				 }
				 
				   /* 输出数量开始 */
				 $query2 = $query.' group by id order by id';
				 $result2 = mysql_query($query2) or die('Query failed: ' . mysql_error());
				 $rcount_q2 = mysql_num_rows($result2);
				 /* 输出数量结束 */
				 $query = $query." order by id desc limit ".$start.",".$end;
				 $result = mysql_query($query) or die('Query failed: ' . mysql_error());
				 $keyid = -1;
				 $batchcode ="";
				 $price =0;
				 $detail ="";
				 $in_money = "";
				 $out_money = "";
				 $createtime = "";
				 $total_in_money = 0;
				 $total_out_money = 0;
				 $after_inventory = 0;
				 $after_getmoney = 0;
				 $withdrawal_id = -1;
	             while ($row = mysql_fetch_object($result)) {
					$keyid = $row->id;
				    $batchcode =$row->batchcode;
				    $price =$row->price;
				    $detail =$row->detail;
				    $type =$row->type;
				    $createtime =$row->createtime;
				    $after_inventory =$row->after_inventory;
				    $after_getmoney =$row->after_getmoney;
				    $withdrawal_id =$row->withdrawal_id;
					
					$query2="select serial_number,remark,confirmtime from weixin_commonshop_withdrawals where isvalid=1 and user_type=1 and id=".$withdrawal_id;
					$result2 = mysql_query($query2) or die('Query failed: ' . mysql_error());
					$confirmtime ="";
					$serial_number ="";
					$remark ="";
					while ($row2 = mysql_fetch_object($result2)) {
						$confirmtime = $row2->confirmtime;
						$serial_number=$row2->serial_number;
						$remark = $row2->remark;
					}
					switch($istype){
						case 3:
							switch($type){
								case 5:
									$price = $price.'元';	//每次进账的金额
									$after_getmoney = $after_getmoney.'元';	//每次结算的库存余额
								break;
								case 6:
									$price = $price.'元';	//每次提现驳回的金额
									$after_getmoney = $after_getmoney.'元';	//每次结算的库存余额
								break;
							}
						break;
					}
					
			   ?>
                <tr>
				   <td><?php echo $keyid; ?></td>
				   <td><?php echo $batchcode; ?></td>
  			       <td><?php echo $price; ?></td>
  			       <td><?php echo $after_getmoney; ?></td>
				   <td><?php echo $createtime; ?></td>
				   <td><?php echo $detail.'</br>';
						if(!empty($confirmtime)){echo '确认时间:'.$confirmtime.'</br>';}
						if(!empty($remark)){echo '提现备注:'.$remark.'</br>';}
				   ?>
				   
				   </td>

				   
                </tr>				
				
			   <?php } ?>
			    <tr>
			      <td colspan=12>
				  <div class="tcdPageCode"></div>
				 </td>
			   </tr>
			</tbody>
				
		</table>

	</div>	
	
</div>
</div>



<?php 

mysql_close($link);
?>
<link type="text/css" rel="stylesheet" rev="stylesheet" href="../css/fenye/fenye.css" media="all">
<script src="../js/fenye/jquery.page.js"></script>
<script>
 var istype = <?php echo $istype ?>;
 var pagenum = <?php echo $pagenum ?>;
 var rcount_q2 = <?php echo $rcount_q2 ?>;
 var end = <?php echo $end ?>;
 var user_id = <?php echo $user_id ?>;
 var customer_id = <?php echo $customer_id_en ?>;
 var count =Math.ceil(rcount_q2/end);//总页数
  	//pageCount：总页数
	//current：当前页
	 $(".tcdPageCode").createPage({
        pageCount:count,
        current:pagenum,
        backFn:function(p){
		 var search_batchcode = document.getElementById("search_batchcode").value;
		 document.location= "supplycost_detail.php?customer_id="+customer_id+"&pagenum="+p+"&user_id="+user_id+"&istype="+istype+"&search_batchcode="+search_batchcode;
	   }
    }); 

</script>

</body></html>