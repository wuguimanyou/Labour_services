<?php
header("Content-type: text/html; charset=utf-8"); //svn
require('../config.php');
require('../customer_id_decrypt.php'); //�����ļ�,��ȡcustomer_id_en[���ܵ�customer_id]�Լ�customer_id[�ѽ���]
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD);
mysql_select_db(DB_NAME) or die('Could not select database');
require('../proxy_info.php');
//require('../common/common_from.php');
$user_id = -1;
if(!empty($_POST["user_id"])){
	$user_id =  $configutil->splash_new($_POST["user_id"]);
}
$user_id =  passport_decrypt((string)$user_id);
$arr              = array();
$info             = array();
$query = "select id,name,imgurl,font_color,shop_name,card_type,num_color,is_show_shopnum,isauto from weixin_cards  where isvalid=true and customer_id=".$customer_id;
$result = mysql_query($query);
//echo $query;
while($row = mysql_fetch_object($result)){
	
	$card_member_id   = "";//��Ա�����ID
	$imgurl           = "";//ͼƬ
	$card_id          = "";//ID
	$font_color       = "";//������ɫ
	$shop_name        = "";//�̵�����
	$card_type        = "";//������
	$num_color        = "";//������ɫ
	$is_show_shopnum  = -1;//�Ƿ���ʾ�̵���
	$card_number      = -1;//��Ա�����
	$shop_card_number = "";//�̵꿨���
	$name             = "";//����
	$isauto           = -1;
	
	$card_id = $row->id;
	$query_own = "select shop_card_number,id from  weixin_card_members where isvalid=true  and user_id=".$user_id." and card_id=".$card_id;
	//echo $query_own;
	$result_own = mysql_query($query_own);
	while($row_own = mysql_fetch_object($result_own)){
		$shop_card_number = $row_own->shop_card_number;
		$card_member_id = $row_own->id;
	}
	$name = $row->name;
	$isauto = $row->isauto;
	$card_number=$card_member_id."";
	$len = strlen($card_number);
	for($i=0;$i<(5-$len);$i++){
	  $card_number = "0".$card_number;
	}
	if($isauto>0){
	   //�Զ�����
	
		 if($card_number==""){
			if( $init_type == 1 ){
				$init_money = 0;
			}elseif( $init_type == 2 ){
				$init_score = 0;
			}
			//û�д������ţ��Զ�����һ��
			$sql6 = "insert into weixin_card_members(card_id,user_id,name,phone,isvalid,createtime) values(".$card_id.",".$user_id.",'','',true,now())";
			mysql_query($sql6);
			$card_member_id=mysql_insert_id();
			
			$query="insert into weixin_card_member_scores (card_member_id,total_score,consume_score,sign_score,remain_score,isvalid) values(".$card_member_id.",".$init_score.",0,0,".$init_score.",true)";
            mysql_query($query);
			$sql = "insert into weixin_card_member_consumes(total_consume,remain_consume,isvalid,card_member_id) values(0,".$init_money.",true,".$card_member_id.")";
			mysql_query($sql);
			$card_number=$card_member_id."";
			$len = strlen($card_number);
			for($i=0;$i<(5-$len);$i++){
			  $card_number = "0".$card_number;
			}
		 }
	}
	$imgurl = $row->imgurl;
	$font_color = $row->font_color;
	$shop_name = $row->shop_name;
	$card_type = $row->card_type;
	$num_color = $row->num_color;
	$is_show_shopnum = $row->is_show_shopnum;
	
	//ͼƬ����
	$pos = strpos($imgurl,"http://");
	$linkurl =InviteUrl.$customer_id;
	if($pos===0){
	}else{
	   $imgurl = BaseURL.$imgurl;  
	}
	
	$arr['name'] = $name;
	$arr['card_id'] = $card_id;
	$arr['font_color'] = $font_color;
	$arr['shop_name'] = $shop_name;
	$arr['card_type'] = $card_type;
	$arr['num_color'] = $num_color;
	$arr['is_show_shopnum'] = $is_show_shopnum;
	$arr['card_number'] = $card_number;
	$arr['shop_card_number'] = $shop_card_number;
	$arr['card_member_id'] = $card_member_id;
	$arr['imgurl'] = $imgurl;
	array_push($info,$arr);
}

echo json_encode($info);
?>