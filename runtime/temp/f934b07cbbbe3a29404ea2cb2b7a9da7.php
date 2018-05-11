<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:48:"D:\sjht/application/manage\view\index\right.html";i:1525917669;s:43:"D:\sjht\application\manage\view\layout.html";i:1524038346;s:50:"D:\sjht\application\manage\view\public\header.html";i:1524887824;s:50:"D:\sjht\application\manage\view\public\footer.html";i:1525226964;}*/ ?>
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
 <div class="layui-fluid layui-form">
	<div class="row" style="margin-left: 0px;color:#000000;">
		<div class="layui-col-sm12 pd-15" style="background: #EEF9F8;box-shadow: 0 1px 2px #CCD0D7;">
			<div class="maintips">
				<span class="f-l f-16 bold mt-5">小草便利店</span>
				<span class="star" tip="专业版"></span>
				<span class="phone" tip="我的店铺"></span>
				<span class="wxdp" tip="微店铺"></span>
				<span class="mess" tip="短信息"></span>
				<span class="wxmess" tip="微信消息"></span>
				<span class="wxpay" tip="微信支付"></span>
				<span class="zpay" tip="支付宝支付"></span>	
				
				<span class="f-r">
					  <div class="layui-form-item">
				    <label class="layui-form-label">切换店铺</label>
				    <div class="layui-input-inline">
				      <select name="city" lay-verify="required">
				      	 <option value=""></option>
				        <?php if(is_array($dplist) || $dplist instanceof \think\Collection || $dplist instanceof \think\Paginator): $i = 0; $__LIST__ = $dplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
					       <option value="0"><?php echo $v['store_name']; ?></option>
				        <?php endforeach; endif; else: echo "" ;endif; ?>
				      </select>
				    </div>
				  </div>
				</span>
			</div>
			
			<hr class="layui-bg-orange">
			<ul class="listitem">
				<li><a href="">短信余额<span class="counter">811</span>条</a></li>
				<li><a href="">库存不足<span class="counter">1</span>个</a></li>
				<li><a href="">生日会员<span class="counter">1</span>个</a></li>
				<li><a href="">订货请求<span class="counter">0</span>条</a></li>
				<li><a href="">货流单数<span class="counter">0</span>条</a></li>
				<li><a href="">报损金额<span class="counter">0</span>元</a></li>
			</ul>			
		</div>
		
		<div class="layui-col-sm8 mt-20">
			<div class="layui-collapse" lay-accordion>
			  <div class="layui-colla-item">
			    <h2 class="layui-colla-title bold">今日概况</h2>
			    <div  class="layui-colla-content layui-show">
			    	<div id="container" >
			    		
			    	</div>
			    	
			    </div>
			  </div>

			</div>
		</div>
		<div class="layui-col-sm4  mt-20 f-r">
			<div class="layui-collapse ml-20" lay-accordion>
			  <div class="layui-colla-item">
			    <h2 class="layui-colla-title bold">通知公告</h2>
			    <div class="layui-colla-content layui-show">
			    	<ul class="newlist">
			    		<li><a href="">官网更新公告！</a></li>
			    		<li><a href="">贵州小草商务电子有限公司将全面进行商品价格调整</a></li>
			    		<li><a href="">用户使用说明...</a></li>
			    		<li><a href="">关于用户费率上调最新通知</a></li>
			    		<li><a href="">官网更新公告！</a></li>
			    		<li><a href="">贵州小草商务电子有限公司将全面进行商品价格调整</a></li>
			    		<li><a href="">用户使用说明...</a></li>
			    		<li><a href="">关于用户费率上调最新通知</a></li>
			    		<li><a href="">官网更新公告！</a></li>
			    		<li><a href="">贵州小草商务电子有限公司将全面进行商品价格调整</a></li>
			    		<li><a href="">用户使用说明...</a></li>
			    		<li><a href="">关于用户费率上调最新通知</a></li>
		    	</ul>
			    	
			    </div>
			  </div>
			</div>
			
		</div>
	</div>	
</div>
	<script src="/public/manage/js/jquery.waypoints.min.js"></script>
	<script type="text/javascript" src="/public/manage/js/jquery.countup.min.js"></script>
	<script type="text/javascript">
		$('.counter').countUp();
	</script>

<script type="text/javascript">
	$('.maintips').on('mouseenter','span',function(){
		
		var that=$(this),tip=$(this).attr('tip');
		if(tip.length>0){
			layer.tips(tip, that,{tips: [1, '#CCC'], time: 2000});
		}
		
	});
</script>
        <script src="https://img.hcharts.cn/highcharts/highcharts.js"></script>
        <script src="https://img.hcharts.cn/highcharts/modules/exporting.js"></script>
        <script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
        <script src="https://img.hcharts.cn/highcharts/themes/grid-light.js"></script>
        <script>
var chart = Highcharts.chart('container', {
	credits:{
     enabled: false // 禁用版权信息
	},
	
    chart: {
        type: 'column',
		spacing: [10,10,60,10],
		marginBottom: 90,        
    },
    title: {
        text: '销售统计'
    },
    xAxis: {
        categories: ['商品销售', '会员消费']
    },
    yAxis: {
        min: 0,
        title: {
            text: '商家销售统计'
        },
        stackLabels: {  // 堆叠数据标签
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
        align: 'right',
        x: -30,
        verticalAlign: 'bottom',
        y: 40,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
        borderColor: '#CCC',
        borderWidth: 1,
        shadow: false
        
    },
    tooltip: {
        formatter: function () {
            return '<b>' + this.x + '</b><br/>' +
                this.series.name + ': ' + this.y + '<br/>' +
                '总量: ' + this.point.stackTotal;
        }
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                style: {
                    // 如果不需要数据标签阴影，可以将 textOutline 设置为 'none'
                    textOutline: '1px 1px black'
                }
            }
        }
    },
    series: [{
        name: '现金支付',
        data: [15, 3]
    }, {
        name: '银联支付',
        data: [12, 2]
    }, {
        name: '储值卡支付',
        data: [8, 4]
    }, {
        name: '次卡支付',
        data: [13, 4]
    }, {
        name: '购物卡支付',
        data: [7, 4]
    }, {
        name: '预付卡支付',
        data: [3, 4]
    }]
});        	
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
