
var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
body = $('body'),
menu_toggle = $('#menu_toggle'),
sidebar_menu = $('#sidebar-menu'),
sidebar_footer = $('.sidebar-footer'),
left_col = $('.left_col'),
right_col = $('.right_col'),
nav_menu = $('.nav_menu'),
footer = $('footer');



// Sidebar
function init_sidebar() {
	var setContentHeight = function () {
		right_col.css('min-height', $(window).height());

		var bodyHeight = body.outerHeight(),
		footerHeight = body.hasClass('footer_fixed') ? -10 : footer.height()+10,
		leftColHeight = left_col.eq(1).height() + sidebar_footer.height(),
		contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;
		contentHeight -= nav_menu.height() + footerHeight;
		right_col.css('min-height', contentHeight);
	};

	sidebar_menu.find('a').on('click', function(ev) {
		/*console.log('clicked - sidebar_menu');*/
		var $li = $(this).parent();
		if ($li.is('.active')) { 		//关闭的效果操作
			$li.removeClass('active active-sm');
			$('ul:first', $li).slideUp();
			$(this).parent().find("a").find("span").removeClass("fa fa-chevron-down");
			$(this).parent().find("a").find("span").addClass("fa fa-chevron-right");
		} else {	
            if (!$li.parent().is('.child_menu')) {   //打开二级菜单
            	sidebar_menu.find('li').removeClass('active active-sm');
            	sidebar_menu.find('li ul').slideUp();

            	//打开、关闭按钮状态切换
            	$li.parent().find("li").each(function(){
            		if($(this).find("a span").hasClass("fa-chevron-down")){
            			$(this).find("a span").removeClass("fa-chevron-down");
            			$(this).find("a span").addClass("fa-chevron-right");
            		}
            	});            	
            	$li.find("a span").removeClass("fa fa-chevron-right");
            	$li.find("a span").addClass("fa fa-chevron-down");
            }else
            {
            	if ( body.is( ".nav-sm" ) )
            	{
            		sidebar_menu.find( "li" ).removeClass( "active active-sm" );
            		sidebar_menu.find( "li ul" ).slideUp();
            	}
            }
            $li.parent().find("li").removeClass("active");
            $li.addClass('active');

            $('ul:first', $li).slideDown();
        }
    });

	menu_toggle.on('click', function() {        
		/*console.log('clicked - menutoggle');*/         
		if (body.hasClass('nav-md')) {
			sidebar_menu.find('li.active ul').hide();
			sidebar_menu.find('li.active').addClass('active-sm').removeClass('active');
		} else {             
			sidebar_menu.find('li.active-sm ul').show();
			sidebar_menu.find('li.active-sm').addClass('active').removeClass('active-sm');
		}     
		body.toggleClass('nav-md nav-sm');     
		setContentHeight(); 
	});
	
	//此处为点击改变右边内容及左边sidebar样式设置，待开发
	//
	//
	//
	//setContentHeight();

	if ($.fn.mCustomScrollbar) {
		$('.menu_fixed').mCustomScrollbar({
			autoHideScrollbar: true,
			theme: 'minimal',
			mouseWheel:{ preventDefault: true }
		});
	}

	
};
$(document).ready(function() {
	init_sidebar();
	$(".body").scroll(function(){
		alert(1);
	})

});	


