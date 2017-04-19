window.addEventListener("load",function(){
    if(parseInt(window.innerHeight)<660&&parseInt(window.innerHeight)>400){
        $("body").css("padding-bottom","20px")
    }
    if(parseInt(window.innerHeight)<500){
        //$("body").css("padding-bottom","50px");
       // $(".main_model_box").css("height",$("html").css("height"));
    }
},false)