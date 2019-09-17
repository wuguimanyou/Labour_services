<?php
// +---------------------------------------------------------+
// |VP值日志                                                 |
// +---------------------------------------------------------+
// |日期:2016-03-24	                                         |	
// +---------------------------------------------------------+
// |By 黄照鸿                                                |
// +---------------------------------------------------------+
header("Content-type: text/html; charset=utf-8"); 
require('../../../config.php');
require('../../../customer_id_decrypt.php'); //导入文件,获取customer_id_en[加密的customer_id]以及customer_id[已解密]
require('../../../back_init.php');
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD);
mysql_select_db(DB_NAME) or die('Could not select database');

require('../../../proxy_info.php');
$user_id = $configutil->splash_new(passport_decrypt($_GET["user_id"]));
$type = $configutil->splash_new($_GET["type"]);

mysql_query("SET NAMES UTF8");
$query2= "select name,phone,weixin_name from weixin_users where isvalid=true and id=".$user_id." and customer_id=".$customer_id." limit 0,1"; 
$result2 = mysql_query($query2) or die('W15 Query failed: ' . mysql_error());
$username    = "/";
$userphone   = "/";
$exceltitle  = "/";
$weixin_name = "/";
while ($row2 = mysql_fetch_object($result2)) {
	$username    = $row2->name;
	$exceltitle  = $row2->name;//生成excel用
	$userphone   = $row2->phone;
	$weixin_name = $row2->weixin_name;
	$username = $username."(".$weixin_name.")";
	break;
}

/* 查询个人总VP值 start */
$my_vpscore     =  0; //个人vp值
$query_vp = "SELECT my_vpscore from weixin_user_vp where isvalid=true and customer_id=" . $customer_id . " and user_id=" . $user_id . " limit 0,1";
$result_vp = mysql_query($query_vp) or die('W447 Query failed: ' . mysql_error());
while ($row_vp = mysql_fetch_object($result_vp)) {
	$my_vpscore  	 = $row_vp->my_vpscore;
}
/* 查询个人总VP值 end */

$search_status= 1;
if(!empty($_GET["search_status"])){
    $search_status = $configutil->splash_new($_GET["search_status"]);
}

?>
<!DOCTYPE html>
<!-- saved from url=(0047)http://www.ptweixin.com/member/?m=shop&a=orders -->
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>vp日志</title>
<link rel="stylesheet" type="text/css" href="../../../common/css_V6.0/content.css">
<link rel="stylesheet" type="text/css" href="../../../common/css_V6.0/content<?php echo $theme; ?>.css">	
<script type="text/javascript" src="../../../common/js/jquery-1.7.2.min.js"></script>
</head>

<body>
<div id="WSY_content">
	<div class="WSY_columnbox" style="min-height: 300px;">
		<div class="WSY_column_header">
			<div class="WSY_columnnav">
				<a class="white1">vp日志</a>
			</div>
		</div>
		<div  class="WSY_data">
			<div id="WSY_list" class="WSY_list">
				<div class="WSY_left" style="background: none;">
					<div class="search">
						<li>是否已打入会员卡：
							<select name="search_status" id="search_status" value="<?php echo $search_status;?>" style="width:100px;" >
								<option value="1" <?php if( 1 == $search_status ){?>selected<?php }?>>确定</option>	
								<option value="2" <?php if( 2 == $search_status ){?>selected<?php }?>>未确定</option>	
							</select>
						</li>
						姓名：<span style="font-weight:bold"><?php echo $username; ?></span>&nbsp;&nbsp;&nbsp; 手机号：<span style="font-weight:bold"><?php echo $userphone; ?></span>&nbsp;&nbsp;&nbsp;
						个人总VP值：<span style="font-weight:bold;font-size:22px;color:red"><?php echo $my_vpscore; ?></span>
					</div>
				</div>
				<li style="margin: 20px 40px 20px 0;float:right;"><a href="javascript:history.go(-1);" class="WSY_button" style="margin-top: 0;width: 60px;height: 28px;vertical-align: middle;line-height: 28px;">返回</a></li>

			</div>
		<table width="97%" class="WSY_table WSY_t2" id="WSY_t1">
			<thead class="WSY_table_header">
				<tr>
					<th width="13%" nowrap="nowrap">id</th>  
					<th width="13%" nowrap="nowrap">订单号</th>  
					<th width="13%" nowrap="nowrap">vp值</th>
					<th width="13%" nowrap="nowrap">来源</th>
					<th width="10%" nowrap="nowrap">状态</th>
					<th width="20%" nowrap="nowrap">备注</th>
					<th width="10%" nowrap="nowrap">时间</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$pagenum = 1;

				if(!empty($_GET["pagenum"])){
				   $pagenum = $configutil->splash_new($_GET["pagenum"]);
				}
				
				$start = ($pagenum-1) * 20;
				$end = 20;
				$query="select id,batchcode,type,vp,status,remark,createtime from weixin_commonshop_vp_logs where isvalid=true and user_id=".$user_id." and customer_id=".$customer_id;
				switch($search_status){		//查vp值是否返到会员卡 status:0未确定，1确定
				    case 2:
					    $query = $query." and status=0";
					break;
				    case 1:
					    $query = $query." and status=1";
					break;				     
				}
				$result_q = mysql_query($query) or die('W82 Query failed5: ' . mysql_error());
				$rcount_q = mysql_num_rows($result_q);
				$query = $query." order by id desc limit ".$start.",".$end;
				//echo $query;
				$log_id     = -1; //日志ID
				$type       =  0; //日志来源。1:订单
				$vp         =  0; //日志vp值
				$status     =  0; //是否已经打入个人weixin_users用户vp总值。0：没 1：已打入
				$createtime = ""; //日志创建时间	
				$remark     = ""; //日志备注
				$batchcode  = ""; //订单号
				$type_str   = ""; //来源说明
				$status_str = ""; //状态说明
				$result = mysql_query($query) or die('W87 Query failed: ' . mysql_error());
				while ($row = mysql_fetch_object($result)) {
						$log_id     = $row->id;
						$type       = $row->type;
						$vp         = $row->vp;
						$status     = $row->status;
						$createtime = $row->createtime;
						$remark     = $row->remark;
						$batchcode  = $row->batchcode;
						
						switch($type){
							case 1:
								$type_str = "订单";
							break;
							default:
								$type_str = "未知";
							break;
						}
						switch($status){
							case 0:
								$status_str = "未确定";
							break;
							case 1:
								$status_str = "已确定";
							break;
							default:
								$status_str = "未知";
							break;
						}
					
					?>
					<tr>
					   <td><?php echo $log_id; ?></td>
					   <td><a href="../../Order/order/order.php?customer_id=<?php echo $customer_id_en; ?>&search_batchcode=<?php echo $batchcode; ?>"><?php echo $batchcode; ?></a></td>
					   <td><?php echo $vp; ?></td>
					   <td><?php echo $type_str; ?></td>
					   <td><?php echo $status_str; ?></td>
					   <td><?php echo $remark; ?></td>
					   <td><?php echo $createtime; ?></td>
					</tr>				
				<?php } ?>
			</tbody>
		</table>
		<div class="blank20"></div>
		<div id="turn_page"></div>
		</div>
		<!--翻页开始-->
        <div class="WSY_page">
        	
        </div>
        <!--翻页结束-->		
	</div>
</div>

<?php 

mysql_close($link);
?>

<script src="../../../js/fenye/jquery.page1.js"></script>
<script>

var customer_id   = '<?php echo $customer_id_en ?>';
var user_id       = '<?php echo passport_encrypt($user_id)?>';
var pagenum       = <?php echo $pagenum ?>;
var rcount_q2     = <?php echo $rcount_q ?>;
var end           = <?php echo $end ?>;
var count         = Math.ceil(rcount_q2/end);//总页数
var page          = count>0?count:1;
var search_status = $('#search_status').val();
//pageCount：总页数
//current：当前页
$(".WSY_page").createPage({
	pageCount:count,
	current:pagenum,
	backFn:function(p){
	document.location= "vp_detail.php?pagenum="+p+"&customer_id="+customer_id+"&user_id="+user_id+"&search_status="+search_status;
   }
});

function jumppage(){
var a=parseInt($("#WSY_jump_page").val()); 
	if((a<1) || (a==pagenum) || (a>page) || isNaN(a)){
		return false;
	}else{
		document.location= "vp_detail.php?pagenum="+a+"&customer_id="+customer_id+"&user_id="+user_id+"&search_status="+search_status;
		
	}
}

//监听选择状态
$("#search_status").change(function(){
  var _ss = $(this).val();
  document.location= "vp_detail.php?pagenum="+pagenum+"&customer_id="+customer_id+"&user_id="+user_id+"&search_status="+_ss;
});

</script>
</body></html>