$(function(){
	//表格行，鼠标放上去变色
	$(".tr:odd").css("background", "#FFFCEA");
	$(".tr:odd").each(function(){
		$(this).hover(function(){
			$(this).css("background-color", "#FFE1FF");
		}, function(){
			$(this).css("background-color", "#FFFCEA");
		});
	});
	$(".tr:even").each(function(){
		$(this).hover(function(){
			$(this).css("background-color", "#FFE1FF");
		}, function(){
			$(this).css("background-color", "#fff");
		});
	}); 

	/*ie6,7下拉框美化*/
    if ( $.browser.msie ){
    	if($.browser.version == '7.0' || $.browser.version == '6.0'){
    		$('.select').each(function(i){
			   $(this).parents('.select_border,.select_containers').width($(this).width()+5); 
			 });
    		
    	}
    }
    var body_height = $("body").height();
    var window_height = $(window).height();
    if(parseInt(body_height) > window_height){
        $(".sidebar_a").css("height",body_height-80);
    }else{
    	$(".sidebar_a").css("height",window_height-80);
    }
 
});


$(document).ready(function(){
	$("form").bind("submit",function(){
		var doms = $(".require");
		var check_ok = true;
		$.each(doms,function(i, dom){
			if($.trim($(dom).val())==''||($(dom).val()=='0'&& $(dom).is("select")))
			{						
					var title = $(dom).parent().parent().find(".left").html();
					if(!title)
					{
						title = '';
					}
					if(title.substr(title.length-1,title.length)==':')
					{
						title = title.substr(0,title.length-1);
					}
					if($(dom).val()=='')
					TIP = LANG['PLEASE_FILL'];
					if($(dom).val()=='0')
					TIP = LANG['PLEASE_SELECT'];						
					alert(TIP+title);
					$(dom).focus();
					check_ok = false;
					return false;						
			}
		});
		if(!check_ok)
		return false;
	});
});


function sel_all(obj){
	var ids = document.getElementsByName("ids[]");
	if(obj.checked == true){
		for(var i=0;i<ids.length;i++){
			if(!ids[i].disabled){
				ids[i].checked = true;	
			}
		}
	}else{
		for(var i=0;i<ids.length;i++){
			ids[i].checked = false;
		}
	}
}


$(document).ready(function(){

	$('.header .userInfo p').click(function() {
	    $(this).parents('.userInfo').children('ul').slideToggle();

	    if ($('.header .userInfo p em').hasClass('icon-down')) {
	        $('.header .userInfo p em').removeClass('icon-down');
	        $('.header .userInfo p em').addClass('icon-up');
	    } else {

	        $('.header .userInfo p em').removeClass('icon-up');
	        $('.header .userInfo p em').addClass('icon-down');
	    }
	});
	$('.header .userInfo ul').click(function() {
	    $(this).hide();
	    $('.header .userInfo p em').removeClass('icon-up');
	    $('.header .userInfo p em').addClass('icon-down');

	});
});







