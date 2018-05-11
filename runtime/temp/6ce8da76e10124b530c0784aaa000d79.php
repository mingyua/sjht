<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:50:"D:\sjht/application/manage\view\member\access.html";i:1525766843;s:43:"D:\sjht\application\manage\view\layout.html";i:1524038346;s:50:"D:\sjht\application\manage\view\public\header.html";i:1524887824;s:50:"D:\sjht\application\manage\view\public\footer.html";i:1525226964;}*/ ?>
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
 <include file="Public:header" />
<div class="container">
	<div class="row" style="margin-top: 20px;padding: 20px;">
		<blockquote class="layui-elem-quote w text-center fa-2x "><b><?php echo $info['Cashier_name']; ?>权限设置</b></blockquote>
		<form class="layui-form" >
		<div class="layui-collapse" >
			<?php if(is_array($menulist) || $menulist instanceof \think\Collection || $menulist instanceof \think\Paginator): $i = 0; $__LIST__ = $menulist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
				<div class="layui-colla-item">
					<h2 class="layui-colla-title"><?php echo $v['Menu_name']; ?></h2>
					<div class="layui-colla-content layui-show">
						<div class="maincate">
							<input name="allck[]" lay-skin="primary" lay-filter="allck" title="全选"  value="<?php echo $v['ID']; ?>" <?php echo $v['checked']; ?>  type="checkbox">  
						</div>
						 <div class="childcate">
						 <?php if(is_array($v['child']) || $v['child'] instanceof \think\Collection || $v['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $v['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$k): $mod = ($i % 2 );++$i;?>
				        	<input name="allck[]" lay-skin="primary" lay-filter="childck" title="<?php echo $k['Menu_name']; ?>" value="<?php echo $k['ID']; ?>" <?php echo $k['checked']; ?> type="checkbox">	
				        	<?php if(is_array($k['child']) || $k['child'] instanceof \think\Collection || $k['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $k['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$j): $mod = ($i % 2 );++$i;?>
					        	<input name="allck[]" lay-skin="primary" lay-filter="childck" title="<?php echo $j['Menu_name']; ?>" value="<?php echo $j['ID']; ?>" <?php echo $j['checked']; ?> type="checkbox">	
				        		
				        	<?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>   
						 	
						 </div>
					</div>
				</div>
				<div class="layui-clear">
					
				</div>
			<?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		  <div class="layui-form-item text-center" style="margin-top: 20px;">
		    <div class="layui-input-block">
		    	<input type="hidden" name="id" id="id" value="<?php echo $info['Cashier_code']; ?>" />
		      <button class="layui-btn" lay-submit lay-filter="send" dataurl="<?php echo URL('member/access'); ?>">立即提交</button>
		      <button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		  </div>		
		</form>
	</div>
</div>
<include file="Public:footer" />
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
