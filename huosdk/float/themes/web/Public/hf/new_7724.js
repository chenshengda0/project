function setTab(name,cursel,n){
for(i=1;i<=n;i++){
   var menu=document.getElementById(name+i);
   var con=document.getElementById("con_"+name+"_"+i);
   menu.className=i==cursel?"hover":"";
   con.style.display=i==cursel?"block":"none";
}
}

$(function(){
	//搜索
	var Wwidth=$(".head").width();
	
	if(Wwidth==320){
		$(".search").width(150)
		$(".search_tx").width(90)
		}else{
		 $(".search").width(Wwidth-185)
		 $(".search_tx").width(Wwidth-250)
		}
	    $(".new_search").width(Wwidth-80)
		$(".new_search_tx").width(Wwidth-140)
	//返回顶部
	 $(window).scroll(function () {
		if ($(window).scrollTop() > 0) {
		   $(".backtop").fadeIn(400);//当滑动栏向下滑动时，按钮渐现的时间
		} else {
		  $(".backtop").fadeOut(200);//当页面回到顶部第一屏时，按钮渐隐的时间
		}
		 /*if ($(window).scrollTop() > 40) {
		    $(".head_altd").addClass("new_head_ad") 
		  } else {
		     $(".head_altd").removeClass("new_head_ad"); 
		  }*/
		});
		$(".backtop").click(function () {
		$('html,body').animate({
		scrollTop : '0px'
		}, 200);//返回顶部所用的时间 
	  });
	  
	  //关闭广告
	  $(".head_altd .ad_close").click(function(){
		     $(".head_altd").hide();
		   }) 
	
	})
	
	
window.onresize=function(){
	  var Wwidth=$(window).width();
	  if(Wwidth>=640){
		   Wwidth=640;
		   $(".index_banner,.roll img,.pic_box").height(240);
		   
		}else{ 
			var allhehe=Wwidth*4
			$(".index_banner,.roll img,.pic_box").height(allhehe/9)
			}
	 $(".index_banner,.roll,.roll img,.roll p,.roll a,.pic_box,.line").width(Wwidth);
	 
	  var newwidth=$(".head").width();
	  if(newwidth==320){
		$(".search").width(150)
		$(".search_tx").width(90)
	  }else{
		 $(".search").width(newwidth-185)
		 $(".search_tx").width(newwidth-250)
	 }
	   $(".new_search").width(newwidth-80)
		$(".new_search_tx").width(newwidth-140)
}