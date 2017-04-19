if(parseInt($("body").css("height"))<parseInt(window.innerHeight)){
    $("html").css("height",window.innerHeight);
    $(".main_model_box").css("height",window.innerHeight);
}
if(parseInt($("html").css("height"))>parseInt(window.innerHeight)){
    $("body").css("padding-bottom","100px");
    $(".main_model_box").css("height",$("html").css("height"));
    if($(".footer_nav").length>0){
        $(".loading_more").css("top",parseInt($("html").css("height"))-90+"px");
    }
}
window.addEventListener("load",function(){
    //$(".main_model_box").css("height",$("body").css("height"));
    /********返回按钮*******/
    $(".header>.main_layout>.close_btn>img").click(function(){
        confirm("你点击了关闭按钮，确认关闭？？");
    });
    /********关闭按钮*******/
    $(".header>.main_layout>.back_btn>img").click(function(){
        confirm("你点击了返回按钮，确认返回？？");
    });
    /********返回顶部按钮*******/
    $(".loading_more>.btn_top").click(function(){
        $("html,body").animate({scrollTop:0}, 500);
    });
    window.onscroll=function(){
        if($(window).scrollTop()>parseInt(window.innerHeight)-300){
            $(".btn_top").css({"position":"fixed","top":parseInt(window.innerHeight)-90+"px","right":"10px"});
            console.log(11);
        }else{
            $(".btn_top").css({"position": "absolute", "top": "0px","right":"10px"});

        }
    };

},false)