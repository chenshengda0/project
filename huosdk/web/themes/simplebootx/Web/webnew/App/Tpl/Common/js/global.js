// JavaScript Document
$(document).ready(function(){
  $(".hot-game li:last-child").css("padding-right","0");
  $(".recommend-good li:last-child").css("border-bottom","none"); 
  $(".recommend-good-game ul li:nth-child(3n+3)").css("margin-right","0"); 
  $(".goods-list ul li:last-child").css("padding-bottom","0");
  $(".goods-list ul li:last-child").css("border-bottom","none");
  $(".new-game ul li:nth-child(6n+6)").css("margin-right","0");
  $(".game-tair li:nth-child(1) i").css("background-color","#fe7b19");
  $(".game-tair li:nth-child(2) i").css("background-color","#ef9653");
  $(".game-tair li:nth-child(3) i").css("background-color","#e7a676");
  $(".video-list .list li:odd").css("float","right");
  $(".all-select .list li a:last-child").css("margin-right","0");
  $(".game-list .list li:even").addClass("fl");
  $(".game-list .list li:odd").addClass("fr");
  $(".form-main .title-update ul li:last-child").css("padding-right","0");
  $(".game-comapre ul li:last-child").css("border-bottom","none");
  $(".game-comapre ul li:first-child").addClass("on");
  $(".game-comapre ul li:nth-child(8n+1) i").css("background-color","#fe7b19");
  $(".game-comapre ul li:nth-child(8n+2) i").css("background-color","#ef9653");
  $(".game-comapre ul li:nth-child(8n+3) i").css("background-color","#e7a676");

  /*幻灯片*/    
  $('#demo01').flexslider({
	  animation: "slide",
	  direction:"horizontal",
	  easing:"swing"
  }); 
  
  $('#demo02').flexslider({
	  animation: "slide",
	  direction:"horizontal",
	  easing:"swing"
  });
  
  $(".game-comapre ul li").each(function(){
    $this = $(this);
    $this.mouseover(function(){
    if($(this).hasClass("on")){return true;}
    $(this).siblings("li").removeClass("on");
    $(this).addClass("on");      
    return false;
    });
  });
  
  $('#tab01 em').click(function(){
	$(this).addClass("on").siblings().removeClass();
	$("#reviews01 > ul").eq($("#tab01 em").index(this)).show().siblings().hide();
  });
  
  $('#tab02 em').click(function(){
	$(this).addClass("on").siblings().removeClass();
	$("#reviews02 > ul").eq($("#tab02 em").index(this)).show().siblings().hide();
  });
  
  $('#tab03 em').click(function(){
	$(this).addClass("on").siblings().removeClass();
	$("#reviews03 > ul").eq($("#tab03 em").index(this)).show().siblings().hide();
  });
  
  
})

function put_card(id){
	$.ajax({
		url:make_card_url,
		type:'post',
		data:{id:id},
		dataType:"json",
		success:function(data){
			if(data.result == 1){
				alert("领取成功");
				window.location.href = user_url;
			}else if(data.result == -2){
				alert("请登录");
				window.location.href = login_url;
			}else{
				alert(data.msg);
			}
		}
	});
}
function del_card(id){
	if(confirm("确认删除卡号记录吗？")){
		$.ajax({
			url:del_card_url,
			type:'post',
			data:{id:id},
			dataType:"json",
			success:function(data){
				if(data.result == 1){
					alert("删除成功");
					location.reload();
				}else if(data.result == -2){
					alert("请登录");
					window.location.href = login_url;
				}else{
					alert(data.msg);
				}
			}
		});
	}
}
