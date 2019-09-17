<?php
  header("Content-type: text/html; charset=utf-8"); 
  require('../config.php');
  require('../customer_id_decrypt.php'); //导入文件,获取customer_id_en[加密的customer_id]以及customer_id[已解密]
  require('../back_init.php');  
 
  $order_id = $configutil->splash_new($_GET["id"]);
  $exp_user_id = $configutil->splash_new($_GET["exp_user_id"]);
  
  $batchcode = $configutil->splash_new($_GET["batchcode"]);
 

$link = mysql_connect(DB_HOST,DB_USER,DB_PWD);
mysql_select_db(DB_NAME) or die('Could not select database');
mysql_query("SET NAMES UTF8");

$query="select sum(totalprice) as totalprice,status,paystatus,sendstatus,return_status,payother_trade_no  from weixin_commonshop_orders where isvalid=true and batchcode='".$batchcode."'";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());  
$totalprice=0;
$status = 0;
$paystatus= 1;
$payother_trade_no= "";//代付商户订单号
$sendstatus = 0;
while ($row = mysql_fetch_object($result)) {
    $totalprice = $row->totalprice;
	$paystatus = $row->paystatus;
	$return_status = $row->return_status;
	$status = $row->status;
	$payother_trade_no = $row->payother_trade_no;
	$sendstatus = $row->sendstatus;
}

$query5="select price from weixin_commonshop_order_express_prices where isvalid=true and batchcode='".$batchcode."' limit 0,1";
$result5 = mysql_query($query5) or die('Query failed: ' . mysql_error());  
$price=0;
while ($row5 = mysql_fetch_object($result5)) {
	$price = $row5->price;
}
if($price>0){
	$totalprice=$totalprice+$price;
}

$query5= "select totalprice from weixin_commonshop_changeprices where status=1 and isvalid=1 and batchcode='".$batchcode."'";
$result5 = mysql_query($query5) or die('Query failed46: ' . mysql_error());

while ($row5 = mysql_fetch_object($result5)) {
	$totalprice = $row5->totalprice;
	break;
}

$totalprice =round($totalprice,2); 

$paystatus_str="未付款";
if($paystatus==1){
   $paystatus_str="已付款";
}

$return_status_str="未退款";
if($return_status==1){
   $return_status_str = "退款中";
}
if(!empty($payother_trade_no)){		//如果是代付.则用代付订单号
	$batchcode = $payother_trade_no;
}
$refund= 0;
$query5="select sum(refund) as refund from weixin_commonshop_refunds where isvalid=true and batchcode='".$batchcode."'";
$result5 = mysql_query($query5) or die('Query failed: ' . mysql_error());
while ($row5 = mysql_fetch_object($result5)) {
   $refund = $row5->refund;
}
/*$sign_type="";
$service_version="";
$sign ="";
$sign_key_index=1;
$trade_mode = 1;
$trade_state=0;
$pay_info="";
$partner="";
$bank_type="";
$bank_billno="";
$total_fee=0;
$fee_type=0;
$notify_id="";
$transaction_id="";
$out_trade_no="";
$attach="";
$time_end="";
$transport_fee=0;
$product_fee=0;
$discount=0;
$buyer_alias="";
$sendstatus = 0;


$weipay_id=-1;
$query ="SELECT id,sign_type,service_version,sign,sign_key_index,trade_mode,trade_state,pay_info,partner,bank_type,bank_billno,total_fee,fee_type,notify_id,transaction_id,out_trade_no,attach,time_end,transport_fee,product_fee,discount,buyer_alias,sendstatus from weixin_weipay_notifys where isvalid=true and attach='1' and out_trade_no='".$order_id."'";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());  
while ($row = mysql_fetch_object($result)) {
    $weipay_id = $row->id;
	$sign_type=$row->sign_type;
	$service_version=$row->service_version;
	$sign =$row->sign;;
	$sign_key_index=$row->sign_key_index;
	$trade_mode = $row->trade_mode;
	$trade_state=$row->trade_state;
	$pay_info=$row->pay_info;
	$partner=$row->partner;
	$bank_type=$row->bank_type;
	$bank_billno=$row->bank_billno;
	$total_fee=$row->total_fee;
	$fee_type=$row->fee_type;
	$notify_id=$row->notify_id;
	$transaction_id=$row->transaction_id;
	$out_trade_no=$row->out_trade_no;
	$attach=$row->attach;
	$time_end=$row->time_end;
	$transport_fee=$row->transport_fee;
	$product_fee=$row->product_fee;
	$discount=$row->discount;
	$buyer_alias=$row->buyer_alias;
	$sendstatus = $row->sendstatus;
}
/*if($weipay_id<0){
   echo "<script>alert('未支付成功！');window.history.go(-1);</script>";
   return;
}

$trade_mode_str="";
if($trade_mode==1){
  $trade_mode_str="即时到账";
}
$trade_state_str="";
if($trade_state==0){
   $trade_state_str="支付成功";
}

$sendstatus_str="未发货";
if($sendstatus==1){
   $sendstatus_str="已发货";
}
mysql_close($link);

*/  

?>
<html>
<head>
<link type="text/css" rel="stylesheet" rev="stylesheet" href="../css/css2.css" media="all">
<link href="../common/add/css/global.css" rel="stylesheet" type="text/css">
<link href="../common/add/css/main.css" rel="stylesheet" type="text/css">
<link href="../common/add/css/shop.css" rel="stylesheet" type="text/css">

<meta http-equiv="content-type" content="text/html;charset=UTF-8">

</head>

<script>
 function submitV(){
    
	
    document.getElementById("keywordFrm").submit();
 }
 
 
</script>

<body>
<div class="div_new_content">

    <div class="add_content_one">
	    订单-微信支付详情
	</div>
	<div id="products" class="r_con_wrap">
	 <div class="r_con_form" >
		<div class="rows">
			<label>交易单号：</label>
			<span class="input">
			<?php echo $batchcode; ?>
			</span>
			<div class="clear"></div>
		</div>
		
		
		<div class="rows">
			<label>总费用</label>
			<span class="input">
			<?php echo $totalprice; ?>
			</span>
			<div class="clear"></div>
		</div>
		
		
		<div class="rows">
			<label>交易状态</label>
			<span class="input">
			<?php echo $paystatus_str; ?>
			</span>
			<div class="clear"></div>
		</div>
		<?php if($refund>0){ ?>
		<div class="rows">
			<label>已退款金额</label>
			<span class="input">
			<?php echo $refund; ?>
			</span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label></label>
			<span class="input">
			   <?php 
			   $query5="select refund,createtime from weixin_commonshop_refunds where isvalid=true and batchcode='".$batchcode."'";
			   $result5 = mysql_query($query5) or die('Query failed: ' . mysql_error());
               while ($row5 = mysql_fetch_object($result5)) {
			      $refund = $row5->refund;
				  $createtime = $row5->createtime;
			?> 
			  <div style="height:20px;">退款金额：<?php echo $refund; ?> &nbsp;&nbsp;&nbsp;退款时间：<?php echo $createtime; ?></div>
			<?php } ?>
			</span>
			<div class="clear"></div>
		</div>
		<?php } ?>
		 <?php if($paystatus==1 and $status<1 and ($sendstatus == 3 || $sendstatus == 5)){ //只有申请退款或退货的才能显示退款操作 ?>
		<div class="rows">
			<label>退款状态</label>
			<span class="input">
			<?php if($return_status > 0){?>
			<?php if($return_status_str==0){ ?>
			    &nbsp;&nbsp;<a href="javascript:showReturn();" style="color:blue" >退款</a><span style="color:red">(请到微信支付设置上传退款证书)</span>
			<?php } ?>
			<div id="div_return" style="display:none">
			    <div style="margin-top:5px;height:30px;line-height:30px;">退款金额:&nbsp;<input type=text value="" id="return_money" />元(不超过订单金额）</div>
				<div style="margin-top:5px;height:30px;line-height:30px;"><input type="button" style="width:80px;height:30px;" value="退款" onclick="subReturn();" /></div>
			</div>
			</span>
			<div class="clear"></div>
		</div>
		<?php }else{ ?>
			<span style="color:red">请先审核退款或退货申请</span>
		<?php }
		 }		?>
	<input type=hidden name="order_id" value="<?php echo $order_id ?>" />
	</div>
</div>

<div style="width:100%;height:20px;">
</div>
<script>
function showReturn(){
   document.getElementById("div_return").style.display="block";
}
var totalprice = <?php echo $totalprice; ?>;
function subReturn(){
   
   var return_money = document.getElementById("return_money").value;
   if(return_money>totalprice){
	  alert("退款金额大于支付金额");
      return;
   }
   document.location = "../common_shop/jiushop/refund.php?customer_id=<?php echo $customer_id_en; ?>&total_fee=<?php echo $totalprice; ?>&out_trade_no=<?php echo $batchcode; ?>&order_id=<?php echo $order_id; ?>&exp_user_id=<?php echo $exp_user_id; ?>&refund_fee="+return_money;
}
</script>
</div>
</body>
</html>

