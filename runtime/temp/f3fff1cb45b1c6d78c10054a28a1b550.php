<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:48:"D:\sjht/application/manage\view\index\index.html";i:1525685195;s:43:"D:\sjht\application\manage\view\layout.html";i:1524038346;s:50:"D:\sjht\application\manage\view\public\header.html";i:1524887824;s:50:"D:\sjht\application\manage\view\public\footer.html";i:1525226964;}*/ ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>贵州小草商务有限公司</title>
		<link href="/public/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
		<link href="/public/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="/public/manage/build/css/custom.css" rel="stylesheet">
		<link href='/public/nprogress/nprogress.css' rel='stylesheet' />
		<link rel="stylesheet" type="text/css" href="/public/manage/css/style.css"/>
		<link rel="stylesheet" type="text/css" href="/public/layui/css/layui.css"/>
		<script src="/public/manage/js/jquery.min.js"></script>
		<script src="/public/layui/layui.js" type="text/javascript" charset="utf-8"></script>

	</head>
	<body class="nav-md">
 
<div class="containers body">
	<div class="main_container">
		<div class="col-md-3 left_col" style="position: fixed;top:0;height:100%;box-shadow: 0 2px 4px #CCD0D7;">
			<div class="left_col scroll-view">
				<div class="navbar nav_title" style="border: 0;">
					<a href="<?php echo URL('Index/index'); ?>" class="site_title"><i class="fa fa-paw"></i> <span>商家后台</span></a>
				</div>
				<div class="clearfix"></div>
				<!-- sidebar menu -->
				<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
					<div class="menu_section">
						<!-- <h3>General</h3> -->
						<ul class="nav side-menu"> 
							
							<?php if(is_array($menudata) || $menudata instanceof \think\Collection || $menudata instanceof \think\Paginator): $i = 0; $__LIST__ = $menudata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
							<li>
								<a><i class="<?php echo $v['Menu_icon']; ?>"></i> <?php echo $v['Menu_name']; ?> <span class="fa fa-chevron-right"></span></a> <?php if(!(empty($v['newarr']) || (($v['newarr'] instanceof \think\Collection || $v['newarr'] instanceof \think\Paginator ) && $v['newarr']->isEmpty()))): ?>
								<ul class="nav child_menu"> <?php if(is_array($v['newarr']) || $v['newarr'] instanceof \think\Collection || $v['newarr'] instanceof \think\Paginator): $i = 0; $__LIST__ = $v['newarr'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$k): $mod = ($i % 2 );++$i;?>
									<li tar="/manage/<?php echo $k['Menu_url']; ?>">
										<a href="javascript:;"><?php echo $k['Menu_name']; ?></a>
									</li> <?php endforeach; endif; else: echo "" ;endif; ?> </ul> <?php endif; ?> </li> <?php endforeach; endif; else: echo "" ;endif; ?> </ul>
					</div>
				</div>
				<!-- /sidebar menu -->
				<!-- /menu footer buttons -->
				<div class="sidebar-footer hidden-small">
					<a title="退出系统" lay-submit lay-filter="jump"  dataurl="<?php echo URL('Login/logout'); ?>" style="width: 100%"> <span style="font-size: 16px;" class="glyphicon glyphicon-off" aria-hidden="true"></span> </a>
				</div>
				<!-- /menu footer buttons -->
			</div>
		</div>
		<!-- top navigation -->
		<div id="headertopright" class="top_nav navbar-fixed-top " style="z-index: 99;background: #4BBCB7;padding-right: 10px;box-shadow: 0 2px 4px #CCD0D7;" >
			<span class="f-l bd-l bd-r">
				<a href="<?php echo URL('index/index'); ?>"><i class="fa fa-tachometer"></i>门店概况</a>
			</span>
			<span class="f-l"><a class="">员工</a></span>
			
			<span class="f-r">
				<a><img src="/public/manage/images/logo2.png" alt=""/></a>
				<a ><i class="fa fa-question-circle"></i>帮助
					<ul>
						<li>帮助热线：</li>
						<li>18285009743</li>
					</ul>
				</a>
				<a ><i class="fa fa-comments-o"></i>客服
					<ul>
						<li>客服热线：</li>
						<li>18285009743</li>
					</ul>
				</a>
				<a >网络状态:<span class="sta">离线</span></a>
				<a ><i class="fa fa-cog"></i>
					<ul>
						<li>账号：<?php echo \think\Session::get('uname'); ?></li>
						<li>店名：<?php echo \think\Session::get('sjname'); ?></li>
						<li><span class="layui-btn" lay-submit lay-filter="jump"  dataurl="<?php echo URL('Login/logout'); ?>">安全退出</span></li>
					</ul>
				</a>				
			</span>
	
		</div>
		<!-- /top navigation -->
		<!-- page content -->
		<div class="right_col" style="height: 100%;">
			<div class="right_main">
				<div id="loading" style='font-size:24px;position:fixed;top:50%;right:45%;'><i class='fa fa-spinner fa-spin' style='font-size:16px;margin-right:5px;vertical-align:middle'></i>页面初始化中...</div>
				<iframe id="rightmain" src="<?php echo URL("Index/right"); ?>" width="100%" height="100%" frameborder=0 marginheight=0 marginwidth=0></iframe>

			</div>
		</div>
		<!-- /page content -->
		<!-- footer content -->
		<footer class="navbar-fixed-bottom">
			<div class="pull-right"> 小草商盟
				<a href="http://www.xiaocaoshop.com" target="__blank">贵州小草商务电子有限公司</a>
			</div>
			<div class="clearfix"></div>
		</footer>
		<!-- /footer content -->
	</div>
</div>
<script type="text/javascript">
	
$(".user-profile").on("mouseenter", function() {
    if ($(this).parent().is(".open")) {
        return;
    }
    $(this).dropdown("toggle");
}).mouseleave(function(){
    if ($(this).parent().is("")) {
        return;
    }
    $(this).dropdown("toggle");	
});
    
    
	$(function() {
		loading();
		$(document).on("click", ".child_menu li", function() {
			link = $(this).attr("tar");
			$('#rightmain').attr('src',link);
			$('#rightmain').hide();
			loading();
		});
		
		function loading(){
			
			$('#loading').show();						
			document.getElementById('rightmain').onload=function(){
				$('#rightmain').show();
				$('#loading').hide();	
			};		
			
		}
		/*个人信息菜单点击事件*/
		$(".profileinfo li").each(function() {
			$(this).click(function() {
				var link = $(this).attr("tar");
				if(link == undefined && $(this).hasClass("modify")) {
					layer.open({
						type: 2,
						title: '密码修改',
						maxmin: true,
						area: ['500px', '336px'],
						content: '<?php echo URL("Profile/modify"); ?>',
					});
					return -1;
				}
				var v = $(this).find("a span").html();
				$.ajax({
					url: link,
					type: 'GET',
					htmlType: 'html',
					success: function(data) {
						$(".right_main").html(data);
					},
					error: function(errMsg) {
						alert(" 页面数据加载失败！");
					}
				});
			});
		});
	})
</script>
		<script src='/public/nprogress/nprogress.js'></script>
		<script src="/public/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="/public/manage/build/js/custom.js"></script>
		<script src="/public/manage/js/jquery.nicescroll.js"></script>

		<script type="text/javascript">
			var statusurl ="/<?php echo $md_name; ?>/<?php echo $ct_name; ?>/status";
			var posturl ="/<?php echo $md_name; ?>/<?php echo $ct_name; ?>/";
			var rootimg = "";
			var access="";
			$('body #kindsheight').niceScroll({
			    cursorcolor: "#ccc",//#CC0071 光标颜色
			    cursoropacitymax: 1, //改变不透明度非常光标处于活动状态（scrollabar“可见”状态），范围从1到0
			    touchbehavior: false, //使光标拖动滚动像在台式电脑触摸设备
			    cursorwidth: "5px", //像素光标的宽度
			    cursorborder: "0", // 游标边框css定义
			    cursorborderradius: "5px",//以像素为光标边界半径
			    autohidemode: false //是否隐藏滚动条
			});		
		</script>
		<script src="/public/manage/js/common.js" type="text/javascript" charset="utf-8"></script>

		
	</body>

</html>
