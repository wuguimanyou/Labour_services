<?php
header("Content-type: text/html; charset=utf-8");     
require('../config.php');
require('../customer_id_decrypt.php'); //导入文件,获取customer_id_en[加密的customer_id]以及customer_id[已解密]
//require('../back_init.php'); 
$link = mysql_connect(DB_HOST,DB_USER,DB_PWD); 
mysql_select_db(DB_NAME) or die('Could not select database');
mysql_query("SET NAMES UTF8");
require('../common/jssdk.php');

//初始化--star
$user_id  	 = 191099;
$customer_id = 3243;
//初始化--star
$start = 0;
$end   = 10;
$query = "SELECT distinct u.id,u.fromw,u.weixin_headimgurl,u.weixin_name,u.parent_id,u.createtime,p.isAgent,p.is_consume FROM weixin_users u left join promoters p on u.id=p.user_id where  u.isvalid=true and p.isvalid=true and match(u.gflag) against (',".$user_id.",') and p.customer_id=".$customer_id." limit ".$start.",".$end;
//echo $query;
$result = mysql_query($query) or die('Query failed1: ' . mysql_error());
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
    <link type="text/css" rel="stylesheet" href="./css/vic.css"/>
    <link type="text/css" rel="stylesheet" href="./css/goods/dialog.css" />
    <link type="text/css" rel="stylesheet" href="./css/goods/wodetuandui.css?v=<?php echo time() ;?>" />
    
    <!-- 页联系style-->
    
    
    
</head>

<!-- Loading Screen -->
<!-- <div id='loading' class='loadingPop'style="display: none;"><img src='./images/loading.gif' style="width:40px;"/></div> -->
<!-- Loading Screen -->

<body data-ctrl=true>
	<!-- header部门-->
 <!-- 	<header data-am-widget="header" class="am-header am-header-default header">
		<div class="am-header-left am-header-nav" onclick="goBack();">
			<img class="am-header-icon-custom header-btn"  src="./images/center/nav_bar_back.png" /><span  class = "header-btn">返回</span>
		</div>
	    <h1 class="header-title">我的团队</h1>
	    <div class="am-header-right am-header-nav">
		</div>
	</header> 
	<div class="topDiv" style="width:100%;height:49px;"></div> -->
	
	<!-- 暂时隐藏头部导航栏 -->
	<!-- header部门-->
	
	<!-- 搜索部门-->
	<div class = "condition">
		<div class = "condition-row1" >
			<input  class="am-form-field search" id="tvKeyword" type="text" style="position:fixed;display:inline;width:94%;text-align:center;border-radius:3px;" placeholder="搜索" >
			<div class="condition-row1-input"><img src="images/vic/icon_search.png" /></div>
		</div>
		<div class = "condition-row2">
			<img class = "condition-row2-btn" id="all_btn" src = "./images/goods_image/2016042901.png" width = "20" height = "20">
			<span class = "condition-row2-text">全部</span>
		</div>
	</div>

	<!-- 搜索部门-->
	
	<!-- content --->
    <div class = "content" id="containerDiv">
    	<ul class = "content-list" id="resultData">
		<?php 
			$p_id              = 0;
			$fromw             = 0;
			$weixin_headimgurl = "";
			$user_name         = "";
			$parent_id         = 0;
			$sq_time           = "";
			$isAgent           = 0;
			$is_consume        = 0;	
			$parent_name       = 0;	
			$i                 = 0;
			while ($row = mysql_fetch_object($result)) {
				$p_id              = $row->id;
				$fromw             = $row->fromw;
				$weixin_headimgurl = $row->weixin_headimgurl;
				$user_name         = $row->weixin_name;
				$parent_id         = $row->parent_id;
				$sq_time           = $row->createtime;
				$isAgent           = $row->isAgent;
				$is_consume        = $row->is_consume;
				
			$sql = "SELECT weixin_name from weixin_users where isvalid=true and id=".$parent_id;
			$result1 = mysql_query($sql) or die('Query failed2: ' . mysql_error());
			while ($row1 = mysql_fetch_object($result1)) {
				$parent_name = $row1->weixin_name;
			}
			//echo $sql;
				$i++;
		?>
    		<li class="itemWrapper">
				<div class = "itemWrapper-main clearfix">
					<div class = "itemWrapper-main-left1">
						<img class = "itemWrapper-main-left1-img" id = "itemWrapper-main-left1-img<?php echo $i; ?>" indexid = "<?php echo $i; ?>" src="<?php echo $weixin_headimgurl; ?>" width="60" height="60">
					</div>				
					<div  class = "itemWrapper-main-left2">					
						<div class = "itemWrapper-main-left2-row1">
							<span><?php echo $user_name; ?></span>
							
							<?php if($is_consume>0){ ?>
							<!--股东昵称start-->
							<span class = "itemWrapper-juese itemWrapper-juese-1"><?php if(1==$is_consume){echo "代理";}elseif(2==$is_consume){echo "渠道";}elseif(3==$is_consume){echo "总经理";}elseif(4==$is_consume){echo "股东";}?></span>
							<!--股东昵称end-->
							
							<!--区域代理昵称start-->
							<?php }elseif(5==$isAgent||6==$isAgent||7==$isAgent||8==$isAgent){
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
							?>
							<span class = "itemWrapper-juese itemWrapper-juese-0"><?php if(0==$is_showcustomer){echo "区代";}elseif(5==$isAgent){echo $a_customer;}elseif(6==$isAgent){echo $c_customer;}elseif(7==$isAgent){echo $p_customer;}elseif(8==$isAgent&&1==$is_diy_area){echo $diy_customer;}elseif(8==$isAgent&&0==$is_diy_area){echo "区代";} ?></span>
							<!--区域代理昵称end-->
							
							<?php }elseif(0==$isAgent){ ?>
							<span class = "itemWrapper-juese itemWrapper-juese-2">推广员</span>
							<?php }elseif(1==$isAgent){ ?>
							<span class = "itemWrapper-juese itemWrapper-juese-5">代理商</span>
							<?php }elseif(3==$isAgent){ ?>
							<span class = "itemWrapper-juese itemWrapper-juese-4">供应商</span>
							<?php }elseif(4==$isAgent){ ?>
							<span class = "itemWrapper-juese itemWrapper-juese-3">技师</span>
							<?php } ?>
						</div>
						<div class = "itemWrapper-main-left2-row2">
							<span>推荐人: <font><?php echo $parent_name; ?></font></span>
						</div>
						<div class = "itemWrapper-main-left2-row3">
							<span><?php echo $sq_time;?></span>
						</div>		
					</div>				
					<div class="itemWrapper-main-right">
						<div  class="itemWrapper-main-right-row1">
							<a href="wodetuandui2.php?persion_id=<?php echo $p_id; ?>&parent_name=<?php echo $parent_name; ?>"><img class = "itemWrapper-main-right-row1-img"  src="./images/vic/right_arrow.png" width="8" height="13" style="" ></a>
						</div>
						<div class = "itemWrapper-main-right-row2">
							<span>来源:</span>
							<?php if(1==$fromw){ ?>
							<img src = "images/goods_image/20160050501.png">
							<?php }elseif(2==$fromw) {?>
							<img src="images/goods_image/20160050502.png">
							<?php }elseif(3==$fromw){ ?>
							<img src="images/goods_image/20160050503.png">
							<?php } ?>
						</div>
					</div>				
				</div>
			</li>
		<?php } ?>
		</ul>		
	</div>
	<!-- content --->	
	
	<!-- dialog--->
	<div class="am-share dlg">
		<div class = "dlg-div">
			<div class = "dlg-div-title">
				<div class = "dlg-div-title-left"><span>角色<span></div>
				<div class = "dlg-div-title-right">
					<span class = "dlg-div-title-right-cell1">
						<font>当前选择</font>
						<font class = "dlg-div-title-right-cell1-text" id = "dlg-div-title-juese">全部</font>
					</span>
				</div>
			</div>
			<div class = "dlg-div-content">
				<div class = "dlg-div-content-item dlg-div-content-item-selected">
					<span> 全部</span>
				</div>
				<div class = "dlg-div-content-item" >
					<span> 推广员</span>
				</div>
				<div class = "dlg-div-content-item">
					<span> 区域代理</span>
				</div>
				<div class = "dlg-div-content-item">
					<span> 股东</span>
				</div>
				<div class = "dlg-div-content-item">
					<span> 分红</span>
				</div>
			</div>
			<div class = "dlg-div-title">
				<div class = "dlg-div-title-left"><span>等级<span></div>
				<div class = "dlg-div-title-right">
					<span class = "dlg-div-title-right-cell1">
						<font>当前选择</font>
						<font class = "dlg-div-title-right-cell1-text" id = "dlg-div-title-dengji">全部</font>
					</span>
				</div>
			</div>
			<div class = "dlg-div-content">
				<div class = "dlg-div-content-item1 dlg-div-content-item1-selected">
					<span> 全部</span>
				</div>
				<div class = "dlg-div-content-item1">
					<span> 一及</span>
				</div>
				<div class = "dlg-div-content-item1" >
					<span> 二级</span>
				</div>
				<div class = "dlg-div-content-item1">
					<span > 三级</span>
				</div>
				<div class = "dlg-div-content-item1">
					<span> 四级</span>
				</div>
				<div class = "dlg-div-content-item1">
					<span> 五级</span>
				</div>
				<div class = "dlg-div-content-item1">
					<span> 六级</span>
				</div>
			</div>
			<div class = "dlg_close_btn">
				<span>确定</span>
			</div>
		</div>
		
		
	</div>
	<!-- dialog--->
    
   
    
</body>		
<!-- 页联系js -->
<script src="./js/goods/global.js"></script>

<div id='loading' class='loadingPop'style="display: none;text-align: center"><img src='./images/loading.gif' style="width:40px;"/><p class=""></p></div>
<script>
var customer_id = <?php echo $customer_id; ?>;
var user_id     = <?php echo $user_id; ?>;
var start       = <?php echo $start; ?>+10;
var end         = <?php echo $end; ?>+10;
var i           = 0;
var page        = 0;

var a_customer   = "<?php echo $a_customer;?>";
var c_customer   = "<?php echo $c_customer;?>";
var p_customer   = "<?php echo $p_customer;?>";
var diy_customer = "<?php echo $diy_customer;?>";

$(window).scroll(function () {
if ($(window).scrollTop() == $(document).height() - $(window).height()) {
$('.loadingPop').show();
$.ajax({//加载团队人员
	   url: "myteam_data.php?customer_id="+customer_id+"&user_id="+user_id+'&statu=member',
	   data:{
		   start:start,
		   end:end	   
		   },
	   type: "POST",
	   dataType:'json',
	   async: true,     
	   success:function(res){
		   start += 10;
		   end += 10;
		   console.log(res);
		    var html = '';
			if(res==''){				//假如无数据则隐藏
			setTimeout(function(){		
				$('.loadingPop').hide();
			},1000);
				return false;
			}
			for(id in res){
				i++;
				html+='<li class="itemWrapper">';
				html+='<div class = "itemWrapper-main clearfix">';
				html+='<div class = "itemWrapper-main-left1">';
				html+='<img class = "itemWrapper-main-left1-img" id = "itemWrapper-main-left1-img'+i+'" indexid = "'+i+'" src="'+res[id]['weixin_headimgurl']+'" width="60" height="60">';
				html+='</div>';
				html+='<div  class = "itemWrapper-main-left2">';
				html+='<div class = "itemWrapper-main-left2-row1">';
				html+='<span>'+res[id]['user_name']+'</span>';
				if(res[id]['is_consume']>0){
					html+='<span class = "itemWrapper-juese itemWrapper-juese-1">';
					if(1==res[id]['is_consume']){
						html+='代理';
					}else if(2==res[id]['is_consume']){
						html+='渠道';
					}else if(3==res[id]['is_consume']){
						html+='总经理';
					}else if(4==res[id]['is_consume']){
						html+='股东';
					}
					html+='</span>';
				}else if(5==res[id]['isAgent']||6==res[id]['isAgent']||7==res[id]['isAgent']||8==res[id]['isAgent']){
					
					html+='<span class = "itemWrapper-juese itemWrapper-juese-0">';
					if(0==res[id]['is_showcustomer']){
						html+='区代';
					}else if(5==res[id]['isAgent']){
						html+=a_customer;
					}
					else if(6==res[id]['isAgent']){
						html+=c_customer;
					}
					else if(7==res[id]['isAgent']){
						html+=p_customer;
					}
					else if(8==res[id]['isAgent']&&0==res[id]['is_diy_area']){
						html+='区代';
					}
					else if(8==res[id]['isAgent']&&1==res[id]['is_diy_area']){
						html+=diy_customer;
					}
					html+='</span>';
				}else if(0==res[id]['isAgent']){
					html+='<span class = "itemWrapper-juese itemWrapper-juese-2">推广员</span>';
				}else if(1==res[id]['isAgent']){
					html+='<span class = "itemWrapper-juese itemWrapper-juese-5">代理商</span>';
				}else if(3==res[id]['isAgent']){
					html+='<span class = "itemWrapper-juese itemWrapper-juese-4">供应商</span>';
				}else if(4==res[id]['isAgent']){
					html+='<span class = "itemWrapper-juese itemWrapper-juese-3">技师</span>';
				}
				html+='</div>';
				html+='<div class = "itemWrapper-main-left2-row2">';
				html+='<span>推荐人: <font>';
				html+=res[id]['parent_name'];
				html+='</font></span>';
				html+='</div>';
				html+='<div class = "itemWrapper-main-left2-row3">';
				html+='<span>'+res[id]['sq_time']+'</span>';
				html+='</div>';
				html+='</div>';
				html+='	<div class="itemWrapper-main-right">';
				html+='<div  class="itemWrapper-main-right-row1">';
				html+="<a href='wodetuandui2.php?persion_id="+res[id]['p_id']+"&parent_name="+res[id]['parent_name']+"'><img class = 'itemWrapper-main-right-row1-img' src='./images/vic/right_arrow.png' width='8' height='13' style='' ></a>";
				html+='</div>';
				html+='<div class = "itemWrapper-main-right-row2">';
				html+='<span>来源:</span>';
				if(1==res[id]['fromw']){
					html+='<img src = "images/goods_image/20160050501.png">';
				}else if(2==res[id]['fromw']){
					html+='<img src="images/goods_image/20160050502.png">';
				}
				else if(3==res[id]['fromw']){
					html+='<img src="images/goods_image/20160050503.png">';
				}
				html+='</div>';
				html+='</div>';
				html+='</div>';
				html+='</li>';
			}
			setTimeout(function(){		
				$('ul').append(html);	//加载数据
				$('.loadingPop').hide();
			},1000);
	  }, 
	   error:function(er){
		console.log(er);
	   }
	});
}
});
</script>
<script>
// condition监听事件
$("#all_btn").click(
	function(){
		//alert("condition按键点击了");
		showConditionDlg();
	}
);

$(".condition-row1-input").click(
	function(){
		var search_text = $('.search').val();
		if(search_text==""){
			return false;
		}
		$('.itemWrapper').remove();
		$('.loadingPop').show();
		$.ajax({//加载团队人员
	   url: "myteam_data.php?customer_id="+customer_id+"&user_id="+user_id+'&statu=member',
	   data:{  
			search_text:search_text	   
		   },
	   type: "POST",
	   dataType:'json',
	   async: true,     
	   success:function(res){
		   console.log(res);
		    var html = '';
			if(res==''){				//假如无数据则隐藏
			setTimeout(function(){		
				$('.loadingPop').hide();
			},1000);
				return false;
			}
			for(id in res){
				i++;
				html+='<li class="itemWrapper">';
				html+='<div class = "itemWrapper-main clearfix">';
				html+='<div class = "itemWrapper-main-left1">';
				html+='<img class = "itemWrapper-main-left1-img" id = "itemWrapper-main-left1-img'+i+'" indexid = "'+i+'" src="'+res[id]['weixin_headimgurl']+'" width="60" height="60">';
				html+='</div>';
				html+='<div  class = "itemWrapper-main-left2">';
				html+='<div class = "itemWrapper-main-left2-row1">';
				html+='<span>'+res[id]['user_name']+'</span>';
				if(res[id]['is_consume']>0){
					html+='<span class = "itemWrapper-juese itemWrapper-juese-1">';
					if(1==res[id]['is_consume']){
						html+='代理';
					}else if(2==res[id]['is_consume']){
						html+='渠道';
					}else if(3==res[id]['is_consume']){
						html+='总经理';
					}else if(4==res[id]['is_consume']){
						html+='股东';
					}
					html+='</span>';
				}else if(5==res[id]['isAgent']||6==res[id]['isAgent']||7==res[id]['isAgent']||8==res[id]['isAgent']){
					
					html+='<span class = "itemWrapper-juese itemWrapper-juese-0">';
					if(0==res[id]['is_showcustomer']){
						html+='区代';
					}else if(5==res[id]['isAgent']){
						html+=a_customer;
					}
					else if(6==res[id]['isAgent']){
						html+=c_customer;
					}
					else if(7==res[id]['isAgent']){
						html+=p_customer;
					}
					else if(8==res[id]['isAgent']&&0==res[id]['is_diy_area']){
						html+='区代';
					}
					else if(8==res[id]['isAgent']&&1==res[id]['is_diy_area']){
						html+=diy_customer;
					}
					html+='</span>';
				}else if(0==res[id]['isAgent']){
					html+='<span class = "itemWrapper-juese itemWrapper-juese-2">推广员</span>';
				}else if(1==res[id]['isAgent']){
					html+='<span class = "itemWrapper-juese itemWrapper-juese-5">代理商</span>';
				}else if(3==res[id]['isAgent']){
					html+='<span class = "itemWrapper-juese itemWrapper-juese-4">供应商</span>';
				}else if(4==res[id]['isAgent']){
					html+='<span class = "itemWrapper-juese itemWrapper-juese-3">技师</span>';
				}
				html+='</div>';
				html+='<div class = "itemWrapper-main-left2-row2">';
				html+='<span>推荐人: <font>';
				html+=res[id]['parent_name'];
				html+='</font></span>';
				html+='</div>';
				html+='<div class = "itemWrapper-main-left2-row3">';
				html+='<span>'+res[id]['sq_time']+'</span>';
				html+='</div>';
				html+='</div>';
				html+='	<div class="itemWrapper-main-right">';
				html+='<div  class="itemWrapper-main-right-row1">';
				html+="<a href='wodetuandui2.php?persion_id="+res[id]['p_id']+"&parent_name="+res[id]['parent_name']+"'><img class = 'itemWrapper-main-right-row1-img' src='./images/vic/right_arrow.png' width='8' height='13' style='' ></a>";
				html+='</div>';
				html+='<div class = "itemWrapper-main-right-row2">';
				html+='<span>来源:</span>';
				if(1==res[id]['fromw']){
					html+='<img src = "images/goods_image/20160050501.png">';
				}else if(2==res[id]['fromw']){
					html+='<img src="images/goods_image/20160050502.png">';
				}
				else if(3==res[id]['fromw']){
					html+='<img src="images/goods_image/20160050503.png">';
				}
				html+='</div>';
				html+='</div>';
				html+='</div>';
				html+='</li>';
			}
			setTimeout(function(){		
				$('ul').append(html);	//加载数据
				$('.loadingPop').hide();
			},1000);
	  }, 
	   error:function(er){
		console.log(er);
	   }
	});
	}
);
</script>
<script src="./js/goods/wodetuandui.js"></script>
</body>
</html>
