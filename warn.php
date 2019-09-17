<?php 
require('public/product_head.php');   
header("Content-type: text/html; charset=utf-8"); 
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>产品管理－警戒</title>
<link rel="stylesheet" type="text/css" href="../../../common/css_V6.0/content.css">
<link rel="stylesheet" type="text/css" href="../../../common/css_V6.0/content<?php echo $theme; ?>.css">
<link rel="stylesheet" type="text/css" href="../../Common/css/Product/product.css"><!--内容CSS配色·蓝色-->
<script type="text/javascript" src="../../../common/js_V6.0/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="../../../common/js_V6.0/jscolor.js"></script><!--拾色器js-->

</head>

<body>
<?php 
require('public/product_type.php');
$head = 14;
?>
<!--内容框架开始-->
<div class="WSY_content" id="WSY_content_height">

       <!--列表内容大框开始-->
	<div class="WSY_columnbox">
    	<?php require('public/head.php');?>
    
	<?php
		$pagenum = 1;

		if(!empty($_GET["pagenum"])){
		   $pagenum = $_GET["pagenum"];
		}

		$start = ($pagenum-1) * 20;
		$end = 20; 
		
		$search_type = -1;
		if($search_type_id>0){
			$search_type = $search_type_id;
		}
		
		$query_child = "SELECT distinct product_id FROM weixin_commonshop_product_prices p , weixin_commonshop_products pro 
			where pro.id = p.product_id and pro.customer_id = ".$customer_id." and pro.isvalid = true and pro.isout = 0 and p.storenum <= ".$stock_remind." and pro.storenum >= ".$stock_remind;
		$result_child = mysql_query($query_child) or die("L225 query error : ".mysql_error());
		$array_child = array();
		$index = 0;
		while($row_c = mysql_fetch_object($result_child)){
			$array_child[$index] = $row_c -> product_id;
		}
		
		$pro_str = implode(",",$array_child);
		//echo "pro_str : ".$pro_str;
		
		$query_count="select count(1) as tcount FROM weixin_commonshop_products WHERE isvalid=true AND customer_id=".$customer_id." 
				and ( storenum < ".$stock_remind."".(empty($pro_str) ? "" : " or id in (".$pro_str.")").") and isout = 0 ";
		
	    $query2="SELECT id,name,asort_value,type_id,type_ids,orgin_price,now_price,default_imgurl,isnew,createtime,isout,ishot,isnew,good_level,meu_level,bad_level,is_supply_id,create_type,sell_count,is_QR ,storenum
			FROM weixin_commonshop_products WHERE isvalid=true AND customer_id=".$customer_id." and ( storenum < ".$stock_remind."".(empty($pro_str) ? "" : " or id in (".$pro_str.")").") and isout=0 "; 
		$query3="";
		if($_SESSION['is_auth_user']=='yes' && $_SESSION['user_id']){
			
			$query2 = $query2." and (auth_users=".$auth_user_id." or is_supply_id>0)";	//授权用户只能看到自己上传的产品;
			$query_count = $query_count." and (auth_users=".$auth_user_id." or is_supply_id>0)";	//授权用户只能看到自己上传的产品和供应商的产品;
		}
		if($keyword!=""){
		   
		   $query3=$query3." AND name like'%".$keyword."%'";
		}
		if($foreign_mark!=""){
		 
		   $query3=$query3." AND foreign_mark like'%".$foreign_mark."%'";
		}
		if($search_type_id>0){
		  
		   // $query3=$query3." AND type_id in (".$search_type.") ";
		   $query3=$query3." AND ( type_ids like '%,".$search_type.",%' or type_ids like '".$search_type.",%' or type_ids like '%,".$search_type."')";
		}
		if($supply_id>0){
		  
		    $query3=$query3." AND is_supply_id = ".$supply_id;
		}
		if($search_source > 0 && $supply_id==0){
			if($search_source == 1){//平台
				$query3=$query3." AND is_supply_id < 0";
			}else if($search_source == 2){
				$query3=$query3." AND is_supply_id > 0";
				
				if($search_supply > 0 ){
					$query3=$query3." AND is_supply_id = ".$search_supply;
				}
			}
		}
		
		if($search_other_id>0){
		   switch($search_other_id){
		      case 1:
			    
			    $query3=$query3." AND isout=true";
			    break;
			  case 2:
			   
			    $query3=$query3." AND isnew=true";
			    break;
			  case 3:
			   
			    $query3=$query3." AND ishot=true";
			    break;
		   }
		}
		 
	
		$query2=$query2.$query3; 
		//echo $query2;
		$query_count=$query_count.$query3;
		/* 输出数量开始 */

		$rcount_q2=1;
		$result2 = mysql_query($query_count) or die('Query failed: ' . mysql_error());
		while ($row2 = mysql_fetch_object($result2)) {
			$rcount_q2=$row2->tcount;
		 }
		//$rcount_q = mysql_num_rows($result2);
		/* 输出数量结束 */
		
		if($sales==1){
		   $query2=$query2."  order by sell_count desc,id desc limit ".$start.",".$end;
		}else{
			$query2=$query2." order by asort_value desc,id desc limit ".$start.",".$end;
		}
		$result2 = mysql_query($query2) or die('Query failed: ' . mysql_error());
	?>
    <!--产品管理代码开始-->
    <div class="WSY_data">
    	<div class="WSY_agentsbox">
        	<div class="WSY_agents WSY_agents001" style="display:none">
                 <li class="WSY_bottonli" id="WSY_bottonli">
                    <input type="button" value="批量删除">
                 </li>
			</div>
		<form class="search" action="warn.php?customer_id=<?php echo $customer_id_en; ?>">
		
			<div class="WSY_search_q">
			<div class="WSY_search_div">
                <li>关键词：<input type="text" id="keyword" name="keyword" value="<?php echo $keyword; ?>"/></li>
                <li>外部标识：<input type="text" name="foreign_mark" id="foreign_mark" value="<?php echo $foreign_mark; ?>" /></li>
				<li>供应商ID：<input type="text" name="supply_id" id="supply_id" value="<?php echo $supply_id; ?>" /></li>
                <li>产品分类：
                    <select name="search_type_id" id="search_type_id">
                        <option value="">--请选择--</option>
						
                    </select>
                </li>
                <li>其他属性：
                   <select name="search_other_id" id="search_other_id">
					<option value="-1">--请选择--</option>
					<option value="2" <?php if($search_other_id==2){?>selected <?php } ?>>新品</option>		
					<option value="3" <?php if($search_other_id==3){?>selected <?php } ?>>热卖</option>	
				</select>
                </li>
				<li>商品来源：
                   <select name="search_source" id="search_source">
					<option value="-1">--所有--</option>
					<option value="1" <?php if($search_source==1){?>selected <?php } ?>>平台</option>		
					<option value="2" <?php if($search_source==2){?>selected <?php } ?>>供应商</option>	
				</select>
                </li>
				<li id="li_supply" <?php if($search_source != 2){?>style='display:none'<?php }?>>供应商：
                   <select name="search_supply" id="search_supply">
					<option value="-1">--所有--</option>
					<?php
						$query_s = "SELECT id ,name,weixin_name FROM weixin_users WHERE isvalid=true 
							AND id in (select distinct is_supply_id from weixin_commonshop_products where isvalid = true and is_supply_id > 0 and customer_id = ".$customer_id.")"; 
						$result_s = mysql_query($query_s);
						while($row_s = mysql_fetch_object($result_s)){
							$s_id = $row_s->id;
							$s_name = $row_s->name;
							$s_weixin_name = $row_s->weixin_name;
					?>
					<option value="<?php echo $s_id;?>" <?php if($search_supply==$s_id){?>selected <?php } ?>><?php echo $s_name."(".$s_weixin_name.")";?></option>		
					<?php }?>
					</select>
                </li>
				<li id="li_sale">
                  <input type="checkbox" name="ordersale" id="ordersale" <?php echo $sales == 1 ? "checked" : "" ;?> style="width:auto;height:auto;"/>
				  <label for="ordersale">按销量排序</label>
                </li>
            	<li class="WSY_bottonliss"><input type="submit" value="搜索"></li>
			</div>
			<div class="WSY_search_div">
				<li class="WSY_bottonliss left" ><input type="button" style="width:100px" id="btn_export" value="导出产品"></li>
				<li class="WSY_bottonliss left" ><input type="button" style="width:100px" id="btn_check_store" value="校对库存"></li>
				<li class="bfont aright">总记录数 <span class="bfont rcolor"><?php echo $rcount_q2;?></span></li>
			</div>
          </div>
		 </form>
		  <div id="div_check_store" class="div_op" style="display:none;height:auto;margin-left:20px">
			<form  id="frm_import" action="../../../excel/import_excel_store.php?customer_id=<?php echo $customer_id_en; ?>&frompage=warn" enctype="multipart/form-data" method="post" class="store">
                <div class="uploader white" style="box-shadow: 0px 0px 0px #ddd;">
					<input type="text" class="filename" readonly/>
					<input type="button" name="file" id="btn_upfile" class="button" value="上传..."/>
					<input  name="excelfile" id="excelfile" type="file" style="display:none"/>
					&nbsp;&nbsp;&nbsp;
					<input type="button" class="button" value="导入库存" onclick="importMember();" style="margin-left:10px;  border-radius: 5px;" />
					&nbsp;&nbsp;&nbsp;
					<a href="../../../excel/store_template.xls" style="line-height:32px">下载模板文件</a>
				</div>
			</form>
          </div>
		  <div id="div_export" class="div_op" style="display:none;height:auto;margin-left:20px">
		   <div class="uploaderbox">
			<em><input type="radio" value="1" id="rdoCond" checked name="exportCond"/> <label for="rdoCond">按当前条件</label></em>
			<em><input type="radio" value="2" id="rdoAll" name="exportCond"/> <label for="rdoAll">所有</label></em>
			<input type="button" class="butqd" value="确定" onclick="exportProduct();">
			</div>
          </div>
            <table width="97%" class="WSY_table" id="WSY_t1">
              <thead class="WSY_table_header">
                <th width="3%"><input id="ck_all" type="checkbox"></th>
               <th width="5%">序号</th>
                <th width="5%">排序(降序)</th>
                <th width="23%">名称</th>
                <th width="12%">属性分类</th>
                <th width="7%">价格</th>
				<th width="4%">销量</th>
				<th width="7%">库存</th>
                <th width="7%">图片</th>
                <th width="7%">属性</th>
                <th width="8%">时间</th>
                <th width="5%">好评/中评/差评</th>
                <th width="8%">操作</th>
              </thead>
			  
			  <?php
	    
		
		$supply_id = -1; //供应商user_id
		$typename = '';
		while ($row2 = mysql_fetch_object($result2)) {
			$p_id=$row2->id;
			$p_name = $row2->name;
			$p_orgin_price = $row2->orgin_price;
			$p_now_price = $row2->now_price;
			$p_isnew= $row2->isnew;
			$p_createtime = $row2->createtime;
			$p_type_id = $row2->type_id;
			$p_isout = $row2->isout;
			$p_isnew= $row2->isnew;
			$p_ishot = $row2->ishot;
			$is_QR = $row2->is_QR;
			$type_ids = $row2->type_ids;
			$asort_value = $row2->asort_value;
			$supply_id = $row2->is_supply_id;
			$create_type = $row2->create_type;
			$sell_count = $row2->sell_count;
			$storenum = $row2->storenum;
		   
		   if(!empty($type_ids)){
			    if(strpos($type_ids,",") === 0){
				   $type_ids = substr($type_ids,1);
			   }
			   if(substr($type_ids,strlen($type_ids)-1) == ","){
				   $type_ids = substr($type_ids,0,strlen($type_ids)-1);
			   }
			   if(!empty($type_ids)){
					$query3="select name from weixin_commonshop_types where isvalid=true and id in (".$type_ids.")";
				   $result3 = mysql_query($query3) or die('L259 : Query failed: ' . mysql_error());
				   $typename="";
				   while ($row3 = mysql_fetch_object($result3)) {
					  $typename = $typename."/".$row3->name;
				   }
			   }
		   }
		  
		   
		   $imgurl = $row2->default_imgurl;
		   if(empty($imgurl)){
			   $query3="select imgurl from weixin_commonshop_product_imgs where isvalid=true and product_id=".$p_id." limit 0,1";
			   $result3 = mysql_query($query3) or die('Query failed: ' . mysql_error());
			   $imgurl="";
			   while ($row3 = mysql_fetch_object($result3)) {
				  $imgurl = $row3->imgurl;
			   }
		   }
		   $otherstr="";
		   if($p_isout){
		      $otherstr=$otherstr."下架";
		   }
		   if($p_isnew){
		      $otherstr=$otherstr."/新品";
		   }
		   if($p_ishot){
		      $otherstr=$otherstr."/热卖";
		   }
		   
		   $good_level=$row2->good_level;
		   $meu_level = $row2->meu_level;
		   $bad_level = $row2->bad_level;
		   
		   
		   $data= BaseURL."common_shop/jiushop/detail.php?pid=".$p_id."&customer_id=".$customer_id;
		   
		   
			$Query2= "SELECT name,phone,weixin_name,weixin_fromuser FROM weixin_users WHERE isvalid=true AND id=".$supply_id; 
			//echo $query2;
			$Result2 = mysql_query($Query2) or die('Query failed35: ' . mysql_error());
			$supply_username="";
			$supply_userphone="";
			$supply_weixin_fromuser="";
			$supply_username = "";
			while ($Row2 = mysql_fetch_object($Result2)) {
				$supply_username=$Row2->name;
				$supply_userphone = $Row2->phone;
				$supply_weixin_fromuser= $Row2->weixin_fromuser;
				$supply_weixin_name=$Row2->weixin_name;
				$supply_username = $supply_username."(".$supply_weixin_name.")";//供应商名称加昵称
				break;
			}
			
			if($supply_id==-1){ $supply_username ="";}//如果不是供应商上传的产品,则为空;
			$shopSupplyName= new createExpQrUtility();
			//$shopSupplyName->mb_substrgb($user_id,$parent_id,$customer_id,1);	//1:商家后台手动改动关系 2:通过分享建立关系 3:推广二维码扫描建立关系;
			$supply_username = $shopSupplyName->mb_substrgb($supply_username,16);//限制文字长度
	  ?>
			  
              <tr id="WSY_q1">
                <td><input type="checkbox" name="pro_ids" value="<?php echo $p_id; ?>"></td>
				<td><?php echo $p_id;?>
					<?php if($supply_id > 0){?>
					<img src="../../../common/images_V6.0/contenticon/gong.png"/>
					<br/>
					<a href="../../Mode/supplier/supply.php?search_user_id=<?php echo $supply_id;?>&customer_id=<?php echo $customer_id_en;?>"
					style="font-weight:bold">
					<?php echo $supply_username;?></a>
					<?php }?>
				</td>
                <td><input class="WSY_sorting" id="<?php echo $p_id; ?>" type="text" value="<?php echo $asort_value; ?>" onblur="change_Sort(<?php echo $p_id; ?>,this)" ></td>
                 <td><span id="proname_<?php echo $p_id;?>" data-proname="<?php echo $p_name?>"><?php echo $p_name; if($is_QR == 1){ echo ' (券)';} ?></span>
					<img id="saveimg_<?php echo $p_id;?>" src="../../../common/images_V6.0/operating_icon/icon53.png" class="ep_img" onclick="toEditName('<?php echo $p_id;?>')"></td>
                <td>
				<span id="protype_<?php echo $p_id;?>"><?php echo $typename; ?></span>
					<img id="savetypeimg_<?php echo $p_id;?>" src="../../../common/images_V6.0/operating_icon/icon53.png" class=" ep_img pro_typeimg" 
						data-pro-typeid="<?php echo $type_ids;?>" data-pro-tparent="<?php echo $tparent_id;?>" data-pro-id="<?php echo $p_id;?>">
				</td>
				<td>
					<div class="WSY_pricebox" style="display: inline-block;">
						<li class="WSY_price01" id="prooprice_<?php echo $p_id;?>" >原价：￥<?php echo $p_orgin_price; ?></li>
						<li id="pronprice_<?php echo $p_id;?>" >现价：￥<?php echo $p_now_price; ?></li>
					</div>
					<img id="savepriceimg_<?php echo $p_id;?>" src="../../../common/images_V6.0/operating_icon/icon53.png" data-pro-id="<?php echo $p_id;?>" data-prooprice="<?php echo $p_orgin_price;?>" data-pronprice="<?php echo $p_now_price;?>"style="padding:10px 0; display: inline-block;" class="ep_img pro_priceimg" > 
				</td>
				<td><?php echo $sell_count; ?></td>
				<td>
					<span id="prostock_<?php echo $p_id;?>" style="float:left;padding:18px 0; "><?php echo $storenum; ?></span>
					<img id="savestockimg_<?php echo $p_id;?>" src="../../../common/images_V6.0/operating_icon/icon53.png" style="padding:15px 0; " data-pro-id="<?php echo $p_id;?>" data-prostock="<?php echo $storenum?>" class="ep_img pro_stockimg">
				</td> 
                <td><img src="<?php echo "http://".$new_baseurl.$imgurl; ?>" class="WSY_fixed"  /></td>
				<td><span id="proattr_<?php echo $p_id;?>" style="float:left;padding:10px 0;"><?php echo $otherstr; ?></span>
					<img id="saveattrimg_<?php echo $p_id;?>" src="../../../common/images_V6.0/operating_icon/icon53.png" 
						class=" ep_img pro_attrimg" style="padding:10px 0;" 
						data-pro-id="<?php echo $p_id;?>" data-isout="<?php echo $p_isout;?>"
						data-ishot="<?php echo $p_ishot;?>" data-isnew="<?php echo $p_isnew;?>">
				</td>
                <td><?php echo $p_createtime; ?></td>
                <td><a href="../comment/discuss.php?customer_id=<?php echo $customer_id_en; ?>&pid=<?php echo $p_id; ?>"><?php echo $good_level."/".$meu_level."/".$bad_level; ?></a></td>
                <td class="WSY_t4" id="WSY_t4">
				<?php if($_SESSION['is_auth_user']=='no' or ($_SESSION['is_auth_user']=='yes' and $p_isout==1)){ // 如果是授权用户,则需要商家下架后才能编辑 或者 商家才能编辑?>
			      <?php if((($owner_general==1 and $create_type==1) or ($owner_general==2 and $create_type==2) or ($owner_general==0 and $create_type==3)) or ($create_type==-1) ){ ?>
                    <a href="add_product.php?head=<?php echo $head?>&customer_id=<?php echo $customer_id_en; ?>&product_id=<?php echo $p_id; ?>&pagenum=<?php echo $pagenum; ?>&adminuser_id=<?php echo $adminuser_id; ?>&owner_general=<?php echo $owner_general; ?>&orgin_adminuser_id=<?php echo $orgin_adminuser_id; ?>"
						title="修改"><img src="../../../common/images_V6.0/operating_icon/icon05.png"></a>
					<?php } 
				}?>
                    <a title="产品推广二维码，扫描即可购买" href="javascript:showMediaMap('<?php echo QRURL."?qrtype=1&customer_id=".$customer_id; ?>&product_id=<?php echo $p_id; ?>&data=<?php echo $data; ?>')"><img src="../../../common/images_V6.0/operating_icon/icon09.png"></a>
				<?php if( 1 > $supply_id and $is_Pcode > 0 ){?>
					<a title="产品防伪二维码" href="code/security_code.php?customer_id=<?php echo $customer_id_en; ?>&product_id=<?php echo $p_id; ?>"><img src="../../../common/images_V6.0/operating_icon/icon71.png"></a>
				<?php } ?>
				<?php if($_SESSION['is_auth_user']=='no' or ($_SESSION['is_auth_user']=='yes' and $p_isout==1)){ // 如果是授权用户,则需要商家下架后才能编辑 或者 商家才能编辑?>
				  <?php if((($owner_general==1 and $create_type==1) or ($owner_general==2 and $create_type==2) or ($owner_general==0 and $create_type==3)) or ($create_type==-1) ){ ?>
				<a href="warn.php?customer_id=<?php echo $customer_id_en; ?>&keyid=<?php echo $p_id; ?>&op=del" onclick="if(!confirm(&#39;删除后不可恢复，继续吗？&#39;)){return false};" title="删除"><img src="../../../common/images_V6.0/operating_icon/icon04.png"></a>
                 <?php }
			}?>
				</td>
                
              </tr>
			  <?php } ?>
            </table>
			
    	</div>
        <!--翻页开始-->
        <div class="WSY_page">
        	
        </div>
        <!--翻页结束-->
    </div>
    <!--产品管理代码结束-->
	</div>
   </div>
 </div>
</div>
<?php 

mysql_close($link);
?>
<script type="text/javascript">
pagenum = '<?php echo $pagenum; ?>';
rcount_q = <?php echo $rcount_q2?>; 
end = <?php echo $end ?>;
count =Math.ceil(rcount_q/end);//总页数
page = count;
customer_id_en = '<?php echo $customer_id_en;?>';
page_index = 3;
ordersale = '<?php echo $sales;?>';
pagename = "warn";
customer_id = '<?php echo $customer_id;?>';
auth_user_id = '<?php echo  $auth_user_id; ?>';
</script>
<!--内容框架结束-->
<script type="text/javascript" src="../../../common/js_V6.0/content.js"></script>
<script src="../../../js/fenye/jquery.page1.js"></script>
<script type="text/javascript" src="../../../common/js/layer/layer.js"></script>
<script type="text/javascript" src="../../Common/js/Product/product_common.js"></script>
<script type="text/javascript" src="../../Common/js/Product/product/warn.js"></script>


</body>
</html>
