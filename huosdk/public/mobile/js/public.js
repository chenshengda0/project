if(parseInt($("html").css("height"))<parseInt(window.innerHeight)){
    $("html").css("height",window.innerHeight+"px");
    if($(".loading_more").length>0||$(".footer_nav").length>0){
        $("body").css("padding-bottom","40px");
        $(".loading_more").css("top",parseInt($("html").css("height"))-100+"px");
        if($(".footer_nav").length>0&&$(".loading_more").length>0){
            $("body").css("padding-bottom","100px");
            $(".main_model_box").css("height",$("html").css("height"));
            $(".loading_more").css("top",parseInt($("html").css("height"))-90+"px");
        }
    }
    $(".main_model_box").css("height",$("html").css("height"));
}
if(parseInt($("html").css("height"))>parseInt(window.innerHeight)){
    //$("body").css("padding-bottom","100px");
    $(".main_model_box").css("height",$("html").css("height"));
    if($(".loading_more").length>0||$(".footer_nav").length>0){
        $("body").css("padding-bottom","40px");
        $(".main_model_box").css("height",$("html").css("height"));
        if($(".footer_nav").length>0&&$(".loading_more").length>0){
            $("body").css("padding-bottom","100px");
            $(".main_model_box").css("height",$("html").css("height"));
            $(".loading_more").css("top",parseInt($("html").css("height"))-90+"px");
        }
    }
}
window.addEventListener("load",function(){
    $(".btn_top").hide();
    $(".loading_more").css("top",$("body").css("height"))
    /********关闭按钮*******/
    $(".header>.main_layout>.close_btn>img").click(function(){
        
    });
    /********返回按钮*******/
    $(".header>.main_layout>.back_btn>img").click(function(){
        window.history.back();
    });
    /********返回顶部按钮*******/
    $(".loading_more>.btn_top").click(function(){
        $("html,body").animate({scrollTop:0}, 500);
    });
    window.onscroll=function(){
        if($(window).scrollTop()>parseInt(window.innerHeight)-300){
            $(".btn_top").css({"display":"block","position":"fixed","top":parseInt(window.innerHeight)-90+"px","right":"10px"});
        }else{
            $(".btn_top").css({"display":"none","position": "absolute", "top": "0px","right":"10px"});

        }
    };
    $("input").click(function(){
        $(".error_box").html("");
    });

},false)