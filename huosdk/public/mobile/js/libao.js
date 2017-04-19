window.addEventListener("load",function(){
    if(parseInt($("body").css("height"))<parseInt(window.innerHeight)){
        $("body").css("height",window.innerHeight);
        $("body").css("padding-bottom","0px");
        $(".main_model_box").css("height",window.innerHeight);
    }
    if(parseInt($("html").css("height"))>parseInt(window.innerHeight)){
        $("body").css("padding-bottom","100px");
        $(".main_model_box").css("height",$("html").css("height"));
    }
    /********领取礼包关闭按钮*******/
    $(".msg_box>.close_btn").click(function(){
        $(".msg_box").hide();
        $(".getBox").hide();
    });
    $(".box>.item>.right>a").each(function(){
        $(this).click(function(){
            if(parseInt($(".getBox").css("width"))>900){
                $(".msg_box").css("margin-top","200px");
            }
            var url=$(this).siblings(".getGiftUrl").val();
            var giftid=$(this).siblings(".giftid").val();
            var app_id=$(this).siblings(".app_id").val();
            $.post(url,{"giftid":giftid,"app_id":app_id},function(data){
                if(data.status==1){
                	//showMsg(".error_box",data.info,'green');
                	$("#activeCode").html(data.info);
                    $(".getBox").show();
                    $(".getBox .msg_box").show();
                }else{
                	showMsg(".error_box",data.info);
                }
            })  
        });
    });
    
    $("#getGift").click(function(){
        var getGiftUrl = $('#getGiftUrl').val();
        var giftid = $("giftid").val();
        var app_id = $("app_id").val();
        $.post(getGiftUrl,{"giftid":giftid,"app_id":app_id},function(data){
            if(data.status==1){
            	showMsg(".error_box",data.info,'green');
            }else{
            	showMsg(".error_box",data.info);
            }
        })   	
    })

},false)