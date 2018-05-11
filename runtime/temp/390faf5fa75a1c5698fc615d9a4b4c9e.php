<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:48:"D:\sjht/application/manage\view\login\index.html";i:1526005817;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>贵州小草商盟后台管理系统</title>

  <link href="/public/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/font-awesome/css/font-awesome.min.css" rel="stylesheet">

  <!-- <link href="/public/plus/vendors/animate.css/animate.min.css" rel="stylesheet"> -->
  <link href="/public/manage/build/css/custom.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="/public/layui/css/layui.css">
  <script src="/public/manage/js/jquery.min.js"></script>
  <script src="/public/layui/layui.js"></script>

  
</head>
<style type="text/css">
	.loginbox{position: absolute;top:45%;margin: 60% 0;padding: 40px; z-index: 9999999;width:100%;background-color:#000000;background:rgba(255,255,255,1);filter:Alpha(opacity=0);box-shadow: 0 1px 1px #ccc;}
	.loginbox .layui-form-item{position: relative;}
	.loginbox i{position: absolute;left: 15px;top:16px;font-size: 20px;}
	.code{position: absolute;right: 2px;top:2px}
	.logo{position: relative;top: -160px;text-align: center;height: 20px;}
	#msg{position: absolute;bottom: 70px;height: 0;color: #f00;font-size: 16px;}
	#ewm{position:absolute;bottom: -28px;right: -29px; width: 53px;height: 53px;transform:rotate(-45deg);
-ms-transform:rotate(-45deg); 	/* IE 9 */
-moz-transform:rotate(-45deg); 	/* Firefox */
-webkit-transform:rotate(-45deg); /* Safari 和 Chrome */
-o-transform:rotate(-45deg); }
#tu{position:absolute;bottom: 0px;left: 0px;display: none;}
</style>
<body>
<div class="layui-carousel" id="test1" lay-filter="test1">
  <div carousel-item="">
    <div><img src="/public/manage/images/banner1.jpg" width="100%" height="100%"/></div>
    <div><img src="/public/manage/images/banner2.jpg" width="100%" height="100%"/></div>
    <div><img src="/public/manage/images/banner3.jpg" width="100%" height="100%"/></div>
    <div><img src="/public/manage/images/banner4.jpg" width="100%" height="100%"/></div>
  </div>
</div> 
<section class="layui-form">
<div class="layui-row">
	<div class="layui-col-sm3 layui-col-lg-offset2">
		<div class="loginbox">
			<div class="logo">
				<img  src="/public/manage/images/login.png"/>
			</div>
			<form>
			<div class="layui-form-item">
					<i class="fa fa-user"></i>
					<input type="text" class="layui-input" style="padding: 0 40px;height: 50px;" name="username" id="" value=""  placeholder="请输入您的手机号码"/>											
			</div>
			<div class="layui-form-item">
					<i class="fa fa-key"></i>
					<input type="password" class="layui-input" style="padding: 0 40px;height: 50px;" name="password" id="" value=""  placeholder="请输入您的密码"/>											
			</div>
			<div class="layui-form-item">
					<i class="fa fa-puzzle-piece"></i>
					<input type="text" class="layui-input" style="padding: 0 40px;height: 50px;" name="code" id="code" value=""  placeholder="请输入验证信息"/>	
					<div class="code"><img src="<?php echo captcha_src(); ?>" width="166" height="49" onclick="this.src='<?php echo captcha_src(); ?>?seed='+Math.random()" id="captcha"></div>
			</div>
			<div class="layui-form-item">					
					<a id="submit" class="layui-btn layui-btn-fluid" style="line-height: 50px;height: 50px;font-size: 20px;" lay-submit="" lay-filter="demo1">登录</a>										
			</div>
			</form>
			<div class="layui-form-item text-right">										
					<a href="javascript:;" class="pwd">忘记密码</a>					
			</div>
			<a id="msg"></a>
			<a id="ewm" style="background: url(/public/manage/images/pull.png) no-repeat;"></a>
			<a id="tu"><img src="/public/manage/images/1.png" width="150" height="150"></a>
	</div>
</div>	
</section>


<script>
layui.use(['form', 'carousel', 'layer'], function(){
  var form = layui.form
  ,layer = layui.layer
  ,carousel = layui.carousel;
  
	carousel.render({
    elem: '#test1'
    ,arrow: 'none'
    ,indicator:'none'
    ,anim:'fade'
    ,full:true
  });
  //监听提交
  form.on('submit(demo1)', function(data){
  	
  	$.ajax({
  		type:"post",
  		url:"<?php echo URL('login/login'); ?>",
  		//async:true,
  		data:data.field,
  		success:function(res){
  			if(res.code==1){
  				layer.msg(res.msg,{icon:res.code,time:3000},function(){  					
  					window.location.href=res.url;
  				});
  			}else{
  				$('#captcha').click();
  				$('#msg').html(res.msg);							
  			}
  			//alert(JSON.stringify(res));
  		},
  		error:function(res){
  			$('#captcha').click();
  			layer.msg('网络错误');
  		}
  	});
  	
  	
    return false;
  });
  
  
});
$(function(){
	$('.pwd').click(function(){
		$('#msg').html('请联系客服人员！');		
		setTimeout('$("#msg").html("")',2000);
	});
	$('#ewm').click(function(){
		$('#tu').slideToggle('slow');
	});
});


document.onkeydown = function(e){
        var ev = document.all ? window.event : e;
        if(ev.keyCode==13) {
         $('#submit').click();
        }
    }
</script>

</body>
</html>
 





