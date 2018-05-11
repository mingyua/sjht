<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:49:"D:\sjht/application/manage\view\member\index.html";i:1525768126;s:43:"D:\sjht\application\manage\view\layout.html";i:1524038346;s:50:"D:\sjht\application\manage\view\public\header.html";i:1524887824;s:50:"D:\sjht\application\manage\view\public\footer.html";i:1525226964;}*/ ?>
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
 <div class="layui-fluid">
	
	<div class="row" style="margin-top: 5px;">
		<div class="layui-col-sm12">
		<div id="headform"><span class="title">员工表</span><button class="layui-btn f-r add" title="添加员工" dataurl="<?php echo URL('member/addmember'); ?>">添加员工</button></div>
			
		</div>

		<table class="table table-bordered">
			<thead>
				<th>工号</th>
				<th>员工姓名</th>
				<th>所属店铺</th>
				<th>手机号码</th>

				<th width="200">操作</th>
			</thead>
			<tbody>
				<?php if(is_array($adminlist) || $adminlist instanceof \think\Collection || $adminlist instanceof \think\Paginator): $i = 0; $__LIST__ = $adminlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
				<tr>
					<td><?php echo $v['Cashier_code']; ?></td>
					<td><?php echo $v['Cashier_name']; ?></td>
					<td ><?php echo getdp($v['Store_code']); ?></td>
					<td><?php echo $v['TEL']; ?></td>


					<td id="usebtn" class="text-center">

						<button class="layui-btn  layui-btn-sm add" title="设置权限" dataurl="<?php echo URL('member/access',array('id'=>$v['Cashier_code'])); ?>">设置权限</button>
						<button class="layui-btn  layui-btn-sm layui-btn-primary add" title="修改" dataurl="<?php echo URL('member/addmember',array('id'=>$v['Cashier_code'])); ?>">修改</button>
						<button class="layui-btn  layui-btn-sm  layui-btn-danger del" arrid="<?php echo $v['Cashier_code']; ?>" dataurl="<?php echo URL('member/delmember'); ?>">删除</button>
					</td>
				</tr>
					
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
			
		</table>
		<div class="text-right">
			<?php echo $adminlist->render(); ?>
		</div>
	</div>

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
