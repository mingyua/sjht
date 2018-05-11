<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:53:"D:\sjht/application/manage\view\member\addmember.html";i:1525767939;s:43:"D:\sjht\application\manage\view\layout.html";i:1524038346;s:50:"D:\sjht\application\manage\view\public\header.html";i:1524887824;s:50:"D:\sjht\application\manage\view\public\footer.html";i:1525226964;}*/ ?>
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
		<form class="layui-form layui-form-pane">
			<div class="layui-form-item"> <label class="layui-form-label">所属店铺</label>
				<div class="layui-input-block"> 
				<select name="storeid" lay-filter="store" lay-verify="required">
					<option value="">请选择</option>
					<?php if(is_array($role) || $role instanceof \think\Collection || $role instanceof \think\Paginator): $i = 0; $__LIST__ = $role;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
						<option value="<?php echo (isset($v['store_code']) && ($v['store_code'] !== '')?$v['store_code']:''); ?>" <?php if($info['Store_code'] == $v['store_code']): ?>selected<?php endif; ?> ><?php echo (isset($v['store_name']) && ($v['store_name'] !== '')?$v['store_name']:''); ?></option>
					<?php endforeach; endif; else: echo "" ;endif; ?>
					
				</select>
				</div>
			</div>

			<div class="layui-form-item"> <label class="layui-form-label">密码</label>
				<div class="layui-input-block"> <input name="password" id="pass" autocomplete="off" <?php if(!(empty($info['Store_code']) || (($info['Store_code'] instanceof \think\Collection || $info['Store_code'] instanceof \think\Paginator ) && $info['Store_code']->isEmpty()))): else: ?>lay-verify="pass"<?php endif; ?> placeholder="请输入密码"  class="layui-input" type="text" value=""> </div>
			</div>
			<div class="layui-form-item"> <label class="layui-form-label">确认密码</label>
				<div class="layui-input-block"> <input name="repassword" autocomplete="off" lay-verify="repass" placeholder="请输入确认密码"  class="layui-input" type="text" value=""> </div>
			</div>
			
			<div class="layui-form-item"> <label class="layui-form-label">姓名</label>
				<div class="layui-input-block"> <input name="Cashier_name" autocomplete="off" placeholder="请输入用户名" class="layui-input" type="text" value="<?php echo (isset($info['Cashier_name']) && ($info['Cashier_name'] !== '')?$info['Cashier_name']:''); ?>"> </div>
			</div>
			<div class="layui-form-item"> <label class="layui-form-label">联系电话</label>
				<div class="layui-input-block"> <input name="TEL" autocomplete="off" <?php if(!(empty($info['id']) || (($info['id'] instanceof \think\Collection || $info['id'] instanceof \think\Paginator ) && $info['id']->isEmpty()))): ?>disabled<?php endif; ?> placeholder="请输入联系电话" lay-verify="tel" class="layui-input" type="text" value="<?php echo (isset($info['TEL']) && ($info['TEL'] !== '')?$info['TEL']:''); ?>"> </div>
			</div>
			<div class="layui-inline"> <label class="layui-form-label">是否可用</label>
				  <input name="statue" value="1" title="是" <?php if(($info['statue'] == '1') OR ($info['statue'] == '')): ?>checked="checked"<?php endif; ?> type="radio">
			      <input name="statue" value="0" title="否" type="radio" <?php if($info['statue'] == '0'): ?>checked="checked"<?php endif; ?>>			
			</div>
			<div class="layui-form-item"> <label class="layui-form-label">备注</label>
				<div class="layui-input-block">
					<textarea name="remark" class="layui-textarea"></textarea>
				</div>
			</div>


			<div class="layui-form-item">
				<input id="storeid" name="Store_code" autocomplete="off"  type="hidden" value="<?php echo (isset($info['Store_code']) && ($info['Store_code'] !== '')?$info['Store_code']:''); ?>">
				<input type="hidden" name="operation_date" id="operation_date" value="<?php echo date('Y-m-d H:i:s',time());?>" />
				<div class="layui-input-block"><a class="layui-btn" lay-submit lay-filter="send" dataurl="<?php echo URL('member/addmember'); ?>">立即提交</a> <button type="reset" class="layui-btn layui-btn-primary">重置</button> </div>
			</div>
			
				<?php if(!(empty($info['Cashier_code']) || (($info['Cashier_code'] instanceof \think\Collection || $info['Cashier_code'] instanceof \think\Paginator ) && $info['Cashier_code']->isEmpty()))): ?>
					<input type="hidden" name="id" value="<?php echo (isset($info['Cashier_code']) && ($info['Cashier_code'] !== '')?$info['Cashier_code']:''); ?>" />			
				<?php endif; ?>
			
		</form>

	</div>
</div>
<script>
	layui.use(['form', 'layer','element'], function() {
	var $ = layui.jquery,		
		form = layui.form,
		layer = layui.layer,	
		element = layui.element;
		

	form.on('select(store)', function(data){			
		$('#storeid').val(data.value);
		alert(JSON.stringify($(this).attr()));
		//layer.msg($(this).attr('tel'));
		form.render();
	});

});
</script>
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
