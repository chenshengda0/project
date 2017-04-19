window.addEventListener("load",function(){
    /********复制按钮*******/
    var clipboard;
    $(".box>.item>.right>a").each(function(i){
        $(this).attr("id","foo"+i);
        $(this).attr("data-clipboard-action","copy");
        $(this).attr("data-clipboard-target","#foo"+i);
        $(this).click(function(event){
            event.preventDefault();
            if(parseInt($(".getBox").css("width"))>900){
                $(".copy_box").css("margin-top","200px");
            }
            var id=$(this).attr("id");
            clipboard= new Clipboard("#"+id);

            clipboard.on('success', function(e) {
                console.log(e);
            });

            clipboard.on('error', function(e) {
                console.log(e);
            })
            $(".getBox").show();
            $(".getBox .copy_box").show();
            clipboard=null;
        });
    });
    $(".getBox").click(function(){
        $(this).hide();
        $(".copy_box").hide();
    });
    $(".footer_nav").css("max-width",$("body").css("width"))
},false)