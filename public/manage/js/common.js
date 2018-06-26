   window.console = window.console || {};
   console.log || (console.log = opera.postError);						   

layui.use(['laydate', 'form', 'layer','table', 'carousel', 'upload', 'element'], function() {
	var laydate = layui.laydate,
		$ = layui.jquery,
		laypage = layui.laypage,
		form = layui.form,
		layer = layui.layer,
		table = layui.table,
		carousel = layui.carousel,
		upload = layui.upload,
		element = layui.element;

  upload.render({
    elem: '.demoMore'
    ,before: function(obj){
    	var item=this.item;
     obj.preview(function(index, file, result){     
        $('.'+item[0].id).html('<img src="'+result+'" width="100%" style="vertical-align: middle;"/>');
     });    	
    },
   
    done: function(res, index, upload){
    	//alert(JSON.stringify(res));
      var item = this.item;
      console.log(res); //获取当前触发上传的元素，layui 2.1.0 新增
      $('#'+item[0].id+'Img').val(res.data);
    }
  });
     //开始日期
    var insStart = laydate.render({
      elem: '#time-start'
      ,min: 0
      ,done: function(value, date){
        //更新结束日期的最小日期
        insEnd.config.min = lay.extend({}, date, {
          month: date.month - 1
        });
        
        //自动弹出结束日期的选择器
       insEnd.config.elem[0].focus();
      }
    });
	laydate.render({
	    elem: '#randtime'
	    ,type: 'datetime'
	    ,range: '到'
	  });    
    //结束日期
    var insEnd = laydate.render({
      elem: '#time-end'
      ,min: 0
      ,done: function(value, date){
        //更新开始日期的最大日期
        insStart.config.max = lay.extend({}, date, {
          month: date.month - 1
        });
      }
    }); 
  lay('.itemtime').each(function(){
    laydate.render({
      elem: this
      ,trigger: 'click'
    });
  });
  
  
  lay('.time-item').each(function(){
    laydate.render({
      elem: this
      ,trigger: 'click'
    });
  });
	form.on('submit(send)', function(data) {
		var url = $(this).attr('dataurl'),_this=$(this);
		//ckaccess(url);
		//layer.msg(JSON.stringify(data.field),{time:20000});exit();
		$.ajax({
			type: "post",
			url: url,
			data: data.field,
			beforeSend: function(){
				_this.removeAttr('lay-submit');
				_this.html('数据正在提交中...');
				//layer.msg('数据正在提交中...',{icon:16});
		    },
			success: function(res) {
				//alert(JSON.stringify(res));return false;
				layer.msg(res.msg, {
					icon: res.status
				});
				if(res.status=='1'){
					setTimeout(function(){
					var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
					parent.layer.close(index); 	
					  parent.location.reload();
					},2000);
				}
				_this.attr('lay-submit','submit');
				_this.html('立即提交');
			},
			error: function(res) {
				_this.attr('lay-submit','submit');
				_this.html('立即提交');
				layer.msg('网络出错！', {
					icon: 2
				});
			}
		});

		return false;
	});

	form.on('submit(jump)', function(data) {
		var url = $(this).attr('dataurl');
		$.ajax({
			type: "post",
			url: url,
			data: data.field,
			beforeSend: function(){
				layer.msg('数据正在提交中...',{icon:16});
		    },
			success: function(res) {

				layer.msg(res.msg, {icon: res.status});
				if(res.url){

					setTimeout(function(){//两秒后跳转  
                       location.href = res.url;//PC网页式跳转                                 
                    },3000); 
				}
			},
			error: function(res) {
				layer.msg('网络出错！', {
					icon: 2
				});
			}
		});

		return false;
	});		
	form.on('select(dpchange)', function(data) {
		var url=$('#dpqhurl').attr('dataurl');
		$.ajax({
			type:'post',
			url:url,
			data:{code:data.value},
			success:function(res){
				console.log(res);
				if(res.status==1){
					top.location.reload();
				}
				layer.msg(res.msg,{icon:res.status});
			},
			error:function(){
				layer.msg('网络出错！', {
					icon: 2
				});
			}
		})		
		

		return false;
	});		
		
	form.on('checkbox(checkall)', function(data){

	    var child = $(data.elem).parents('.layui-form').find('input[name="allck[]"]');
	    child.each(function(index, item){

	        item.checked = data.elem.checked;
	    });
	    form.render('checkbox');
	});	
	
	form.on('checkbox(allck)', function(data){

	    var child = $(data.elem).parents('.layui-colla-content').find('.childcate input[name="allck[]"]');
	    child.each(function(index, item){

	        item.checked = data.elem.checked;
	    });
	    form.render('checkbox');
	});
	form.on('checkbox(childck)', function(data){

	    var child = $(data.elem).parents('.layui-colla-content').find('.childcate input[name="allck[]"]:checked');
	    if(child.length>=1){
	    	var ck=true;
	    }else{
	    	var ck=false;
	    }
	    var mainck = $(data.elem).parents('.layui-colla-content').find('.maincate input[name="allck[]"]');	    	
		    mainck.each(function(index, item){
		        item.checked = ck;
		    });

	    form.render('checkbox');
	});	
	  form.on('submit(allroleck)', function(data){
	    layer.msg(JSON.stringify(data.field));
	    return false;
	  });	

	form.on('switch(switch)',function(){
		var id=$(this).attr('dataid');
		$.ajax({
			type:"post",
			url:statusurl,
			data:{id:id},
			success:function(res){
				layer.msg(res.msg,{icon:res.status});
			},
			error:function(){
				layer.msg('网络错误！');
			}
		});
	});

	
	
 //自定义验证规则
  form.verify({
 	pass:function(value){
  		if(value){		  
	      var ret = /^[0-9A-Za-z]{6,12}$/;
	      if(!ret.test(value)){
	       return '密码必须6到12位';	      
	      } 			
  		}
  		
  	},
    repass: function(value){
    	var pass=document.getElementById('pass').value;
    	if(pass.length>0){
	      if(value!==pass){
	        return '确认密码与密码不一致';
	      }  
			if(pass.length<6){
	    		return '密码必须6到12位';
	    	}
			if(pass.length>12){
	    		return '密码必须6到12位';
	    	}	      
			
    	} 
    },
    tel:[/^[1][3,4,5,7,8][0-9]{9}$/,'手机号码不正确'],
    zh:[/^[\u4E00-\u9FA5]{1,6}$/,'必须为中文'],
    num:[/^[0-9]{1,12}$/,'只能输入数字,最多12位'],
    fileimg:[/\S/,'必须上传证件'],
  });	
});
$(function() {
	
		function ckaccess(url){ 
			var news=url.match(/\/([a-zA-Z])*\/([a-z])*\/([a-zA-Z])*/)[0].replace(/\/[a-zA-Z]*\//,'');
			var ace = access.indexOf(news);
			if(ace<0){
				layer.msg('您没有操作权限，请联系管理员！',{icon:2});
				exit();
			}
		}
	$('body').on('click', '.add', function() {
		var url = $(this).attr('dataurl'),
			title = $(this).attr('title');
			//ckaccess(url);
		var index = layer.open({
			title: title,
			type: 2,
			area: ['70%','100%'],
			maxmin: true,
			content: url
		});
		//layer.full(index);
	});
	$('.fontawesome-icon-list').on('click', 'a', function() {
		$('#menuico').val($(this).find('i').attr('class'));
		//alert();
	});

	$('body').on('click', '.del', function() {

		var dd = $(this);
		var url = $(this).attr('dataurl');
		var id = $(this).attr('arrid');
		//ckaccess(url);
		layer.confirm('您确定要删除吗？', function(index) {
			$.ajax({
				type: "post",
				url: url,
				data: {
					id: id
				},
				success: function(res) {
					layer.msg(res.msg, {
						icon: res.status
					});
					dd.parents("tr").remove();
					dd.parents(".brandbox").remove();
				},
				error: function(res) {
					layer.msg('网络出错！', {
						icon: 2
					});
				}
			});
		});
	});

	$('body').on('click', '#order', function() {

		var dd = $(this);		
		var id = $(this).attr('dataid');
		$.ajax({
			type: "post",
			url: url,
			data: {
				id: id,
				order: order
			},
			success: function(res) {
				layer.msg(res.msg, {
					icon: res.status
				});

			},
			error: function(res) {
				layer.msg('网络出错！', {
					icon: 2
				});
			}
		});

	});
	$('body').on('click', '.addpro', function() {
		
		var url=$(this).attr('dataurl'),id=$(this).attr('dataid'),title=$(this).attr('title'),val=$(this).attr('dataname');
		layer.prompt({
			title: '输入'+title+'名称，并确认',
			value: val,
			
		}, function(name, index) {
			layer.close(index);
			$.ajax({
				type: "post",
				url: url,
				data: {name:name,id:id},
				success: function(res) {
					layer.msg(res.msg, {
						icon: res.status
					});
					
				},
				error: function(res) {
					layer.msg('网络出错！', {
						icon: 2
					});
				}
			});			
		});
	});

	$('body').on('click', '.status', function() {
		var url=statusurl;
		$.ajax({
			type:"post",
			url:statusurl,
			data:{id:$(this).attr('dataid')},
			success:function(res){
				if(res.status==1){
					layer.msg(res.msg,{icon:res.status,time:1000},function(index){
						window.location.reload();
					});
				}else{
					layer.msg(res.msg,{icon:res.status});
				}
				
			},
			error:function(index){
				layer.msg('网络错误',{icon:2});
			}
			
		});
	});
	
//当键盘键被松开时发送Ajax获取数据
	$('#stname').keyup(function(){
		var keywords = $(this).val();
		if (keywords=='') { $('#word').hide(); return };
		$.ajax({
			url:jsonurl+'?wd=' + keywords, //'http://suggestion.baidu.com/su?wd=' + keywords,
			dataType: 'json',
			json: 'cb', //回调函数的参数名(键值)key
			// jsonpCallback: 'fun', //回调函数名(值) value
			beforeSend:function(){
				$('#word').append('<div>正在加载。。。</div>');
			},
			success:function(data){
				//alert(JSON.stringify(data.s));
				$('#word').empty().show();
				if (data.s=='')
				{
					$('#word').append('<div class="error">Not find  "' + keywords + '"</div>');
				}
				$.each(data.s, function(){
					$('#word').append('<div class="click_work" dataarr="'+this.store_name+'|'+this.TEL+'|'+this.Address+'|'+this.email+'|'+this.N_code+'|'+this.Lianxir+'">'+ this.store_name +'</div>');
				})
			},
			error:function(){
				$('#word').empty().show();
				$('#word').append('<div class="click_work">Fail "' + keywords + '"</div>');
			}
		})
	})
//点击搜索数据复制给搜索框
	$(document).on('click','.click_work',function(){		
		var _this=$(this),word = $(this).text(),dataarr=$(this).attr('dataarr'),v = dataarr.split("|");		
		$('#user_code').val(v[1]);
		$('#number_code').val(v[4]);
		$('#Address').val(v[2]);
		$('#email').val(v[3]);
		$('#TEL').val(v[1]);		
		$('#Lianxir').val(v[5]);		
		$('#stname').val(word);
		_this.parents('.layui-input-inline').find('#autoload').val(word);
		_this.parents('.autobox').hide().next().val(dataarr);
		$('#word').hide();
		
		
		//alert(dataarr);
		// $('#texe').trigger('click');触发搜索事件
	});
	
	$('body').on('click','.allstatus',function(){		
		var id=$('input[name="allck[]"]').val(),type=$(this).attr('datatype'),url=$(this).attr('dataurl');
		var ckval =[];
		$('input[name="allck[]"]:checked').each(function(){
			ckval.push($(this).val());
		}); 		

		if(ckval.length==0){
			layer.msg('你还没有选择任何内容！'); 
			return false;
		}
		$.ajax({
			type:"post",
			url:posturl+url,
			data:{id:ckval,type:type},
			success:function(res){
				layer.msg(res.msg,{icon:res.status});
				if(res.status==1){
					//window.location.reload();
				}
			},
			error:function(){
				layer.msg('网络错误！');
			}
		});
		
	});
	

});

document.onkeydown = function(e){
        var ev = document.all ? window.event : e;
        if(ev.keyCode==13) {
         $('#submit').click();
        }
    }
