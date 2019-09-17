<?php
  header("Content-type: text/html; charset=utf-8"); 
  require('../config.php');
  require('../customer_id_decrypt.php'); //导入文件,获取customer_id_en[加密的customer_id]以及customer_id[已解密]
  require('../back_init.php');  
 
  $batchcode = $_GET["batchcode"];

 

$link = mysql_connect(DB_HOST,DB_USER,DB_PWD);
mysql_select_db(DB_NAME) or die('Could not select database');
mysql_query("SET NAMES UTF8");

$sign_type="";
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
$query ="SELECT id,sign_type,service_version,sign,sign_key_index,trade_mode,trade_state,pay_info,partner,bank_type,bank_billno,total_fee,fee_type,notify_id,transaction_id,out_trade_no,attach,time_end,transport_fee,product_fee,discount,buyer_alias,sendstatus from weixin_weipay_notifys where isvalid=true and attach='1' and out_trade_no='".$batchcode."'";
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
if($weipay_id<0){
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

$sendstatus_str="未确认发货接口";
if($sendstatus==1){
   $sendstatus_str="已确认发货接口";
}
mysql_close($link);

  
 
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
			<?php echo $transaction_id; ?>
			</span>
			<div class="clear"></div>
		</div>
		
		<div class="rows">
			<label>商户号：</label>
			<span class="input">
			<?php echo $partner; ?>
			</span>
			<div class="clear"></div>
		</div>
		
		<div class="rows">
			<label>付款银行：</label>
			<span class="input">
			<?php echo $bank_type; ?>
			</span>
			<div class="clear"></div>
		</div>
		
		<div class="rows">
			<label>总费用</label>
			<span class="input">
			<?php echo $total_fee; ?>
			</span>
			<div class="clear"></div>
		</div>
		
		<div class="rows">
			<label>币种</label>
			<span class="input">
			<?php echo $fee_type; ?>
			</span>
			<div class="clear"></div>
		</div>
		
		<div class="rows">
			<label>交易模式</label>
			<span class="input">
			<?php echo $trade_mode_str; ?>
			</span>
			<div class="clear"></div>
		</div>
		
		<div class="rows">
			<label>交易状态</label>
			<span class="input">
			<?php echo $trade_state_str; ?>
			</span>
			<div class="clear"></div>
		</div>
		
		<div class="rows">
			<label>发货接口状态</label>
			<span class="input">
			<?php echo $sendstatus_str; ?>
			<?php if($sendstatus==0){ ?>
			    &nbsp;&nbsp;<a href="add_order_deliver.php?customer_id=<?php echo $customer_id_en; ?>&batchcode=<?php echo $batchcode; ?>" style="color:blue" >确认发货接口</a>
			<?php } ?>
			</span>
			<div class="clear"></div>
		</div>
	
		
		
		
	<input type=hidden name="batchcode" value="<?php echo $batchcode ?>" />
	</div>
</div>

<div style="width:100%;height:20px;">
</div>

</div>
</body>
</html>

