$(".info_list>li>.gender>div").each(function(){
    $(this).click(function(){
        $(this).siblings().find("i").removeClass("active");
        $(this).find("i").addClass("active");
    });
});
$(".headImage").click(function(){
	$("#fileInput").click();
});


var TimeFn;
$(".info_list>li>span").each(function(){
    this.dbClick=false;
    $(this).click(function(){
        if(this.dbClick){
            if(!$(this).parent().hasClass("noCan")){
                var input = $("<input type='text'/>");
                console.log($(this).html());
                input.val($(this).html());
                $(this).parent().append(input);
                $(this).remove();
            }
        }
        this.dbClick=true;
        console.log("click");
        // 取消上次延时未执行的方法
        clearTimeout(TimeFn);
        //执行延时
        TimeFn = setTimeout(function(){
            this.dbClick=false;
        },300);
    });

});
$(".confim_change>button").click(function(){
    var name=$("#name>input").val();
    var gender=$("#gender").find(".active").parent().siblings("span").attr("data-id");
    var email=$("#email>input").val();
    if(!name){name=$("#name>input").val()}
    if(!email){email=$("#email>input").val()}
    var url=$("#url").val();
    sendData(url,{"name":name,"gender":gender,"email":email},function(){
        alert("已发送数据..");
    })
});