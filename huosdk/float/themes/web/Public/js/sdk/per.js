// tab切换
function setTab(name,cursel,n){
for(i=1;i<=n;i++){
   var menu=document.getElementById(name+i);
   var con=document.getElementById("con_"+name+"_"+i);
   menu.className=i==cursel?"hover":"";
   con.style.display=i==cursel?"block":"none";
}
}

// JavaScript Document
$(function(){
	//删除游戏
	$("#del").click(function(){
		if($(this).html()=="删除"){
			$(".list_one").find(".del").show();
			$(this).html("完成");
			}else{				
				$(".list_one").find(".del").hide();
				$(this).html("删除");
				}		
		})
	

	$(".opacity_bg,.close,.cancel").click(function(){
		$(".opacity_bg,.tishi_box").hide();		
		})
	//获得焦点
	$(".input01,.input02,.input03").focus(function(){
		$(this).css("color","#48515c");	
		$(this).parent("li").css("border-bottom","1px solid #bac2cc");	
		})
	$(".input01,.input02,.input03").blur(function(){
		$(this).css("color","#48515c");		
		$(this).parent("li").css("border-bottom","1px solid #e5e5e5");	
		})
	//同意条款
	$(".agree").click(function(){
		$(".checkbox").toggleClass("checkboxed");		
		})
	//性别选择
	$(".sex .man").click(function(){
		$("#sex").val(1);
		$(this).addClass("on1").next(".woman").removeClass("on2");				
		})
	$(".sex .woman").click(function(){
		$("#sex").val(0);
		$(this).addClass("on2").prev(".man").removeClass("on1");			
		
		})
	//返回顶部
	 $(window).scroll(function () {
		if ($(window).scrollTop() > 0) {
		$(".backtop").fadeIn(400);//当滑动栏向下滑动时，按钮渐现的时间
		} else {
		$(".backtop").fadeOut(200);//当页面回到顶部第一屏时，按钮渐隐的时间
		}
		});
		$(".backtop").click(function () {
		$('html,body').animate({
		scrollTop : '0px'
		}, 200);//返回顶部所用的时间 
	  });
})