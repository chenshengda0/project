<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="textml; charset=UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />
<title><?php echo ($title); ?></title>
<link rel="stylesheet" type="text/css" href="/public/float/css/float.css" />
</head>
<body>
<!--
<div class="modular">
    <ul>
        <li class="back"><a onclick="history.go(-1);"><img src="/public/float/images/goback.png"><span class="main_title"><?php echo ($title); ?></span></a></li>
        <li><a onclick="window.mgw_web_back.goToGame()">回到游戏</a></li> 
    </ul>
</div>-->

<div class="chongzhi">
     <ul class="chongzhi1">
        <li class="zf_select" data-id="2">已支付</li>
        <li data-id="1">未支付</li>
        <li data-id="3">支付失败</li>
     </ul>
</div>
<div class="binding00">
    <div class="yzf div_show">
    </div>
    <div class="wzf">
    </div>
    <div class="zfsb">
    </div>
</div>
<footer style="margin-bottom:15px;">
    <button>加载更多</button>
</footer>
</body>
<script src="/public/float/js/jquery.js"></script>
<script src="/public/float/js/main.js"></script>
<script>
	window.onload=function(){
		$("footer button").click();
	}
    /********框高度*******/
   $(".binding00").css("height",$(".div_show").css("height"))
    var page1=0;
    var page2=0;
    var page3=0;
    var page4=0;
    var y1=5;
    var y2=5;
    var y3=5;
    var y4=5;
    $(".chongzhi1 li").each(function(i){
        this.index=i;
    });
    $(".chongzhi1").delegate("li","click",function(){
        $(".binding00 div").each(function(){
            $(this).css("display","none");
        });
        $(".binding00 div").eq(this.index).css("display","block");
        $(".binding00 div").eq(this.index).addClass("div_show").siblings().removeClass("div_show");
        $(this).siblings().removeClass("zf_select");
        $(this).addClass("zf_select");
        $(".binding00").css("height",$(".div_show ul").length*175+"px");
        if($(".zf_select").attr("data-id")==1){
            y4=y1;
            page4 = page1;
        }else if($(".zf_select").attr("data-id")==2){
            y4=y2;
            page4 = page2;
        }else{
            y4=y3;
            page4 = page3;
        }
        
        if(y4<5){
            $("footer button").attr("disabled",true);
            $("footer button").attr("class","jz_more");
        }else{
            $("footer button").attr("disabled",false);
            $("footer button").attr("class","");
            if (page4==0 ){
            	$("footer button").click();
            }
        }
    })
    /*********ajax请求*********/

    $("footer button").click(function(event){
       if($(".zf_select").attr("data-id")==1){
            page4=page1;
            var url = "<?php echo U('Pay/pay',array('stat'=>1));?>";
        }else if($(".zf_select").attr("data-id")==2){
            page4=page2;
            var url = "<?php echo U('Pay/pay',array('stat'=>2));?>";
        }else{
            page4=page3;
            var url = "<?php echo U('Pay/pay',array('stat'=>3));?>";
        }
        $.get(url+"&page="+page4).success(function(data){
            var x=JSON.parse(data);
            /* console.log(x); */
            var xlen = x.length;
            if($(".zf_select").html()==="已支付"){
				y2=xlen;
            	for (var i=0;i<xlen;i++){
            		console.log(x[i].gmname);
            		page2++;
            		$(".yzf").html($(".yzf").html()+"<ul class='chongzhi4 '><li ><span>充值到：</span><b>"+ x[i].gamename+"</b></li><li ><span>支付金额：</span><b>"+x[i].money+"元</b></li> <li ><span>支付状态：</span><b>"+x[i].stat+"</b></li> <li ><span>订单号：</span><b>"+x[i].order_id+"</b></li><li ><span>支付时间：</span><b>"+x[i].c_time+"</b></li>\</ul>")
            	}
                if(xlen<5){
                    $("footer button").attr("disabled",true);
                    $("footer button").attr("class","jz_more");
                }
            }else if($(".zf_select").html()==="未支付"){
				y1=xlen;
                for(var i=0;i<xlen;i++) {
                	page1++;
                	$(".wzf").html($(".wzf").html()+"<ul class='chongzhi4 '><li ><span>充值到：</span><b>"+ x[i].gamename+"</b></li><li ><span>支付金额：</span><b>"+x[i].money+"元</b></li> <li ><span>支付状态：</span><b>"+x[i].stat+"</b></li> <li ><span>订单号：</span><b>"+x[i].order_id+"</b></li><li ><span>支付时间：</span><b>"+x[i].c_time+"</b></li>\</ul>")
                }
                if(xlen<5){
                    $("footer button").attr("disabled",true);
                    $("footer button").attr("class","jz_more");
                }
            }else if($(".zf_select").html()==="支付失败"){
				y3=xlen;
                for(var i=0;i<xlen;i++) {
                	page3++;
                	$(".zfsb").html($(".zfsb").html()+"<ul class='chongzhi4 '><li ><span>充值到：</span><b>"+ x[i].gamename+"</b></li><li ><span>支付金额：</span><b>"+x[i].money+"元</b></li> <li ><span>支付状态：</span><b>"+x[i].stat+"</b></li> <li ><span>订单号：</span><b>"+x[i].order_id+"</b></li><li ><span>支付时间：</span><b>"+x[i].c_time+"</b></li>\</ul>")
                }
                if(xlen<5){
                    $("footer button").attr("disabled",true);
                    $("footer button").attr("class","jz_more");
                }
            }
            $(".binding00").css("height",$(".div_show").css("height"))
        });
    });
</script>
</html>