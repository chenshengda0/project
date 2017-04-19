 $("header>h3").css("transform","translateX(-"+parseInt($("header>h3").css("width"))/2+"px)");
    /*****充值金额*****/
    $(".recharge_amount>.btn_group>li").each(function(i){
        this.index=i+1;
        if(this.index%3===0){
            $(this).css("margin-right","0px");
        }
        $(this).click(function(){
            var money=$(this).html();
            $(".btn_group>input").val("");
            $(this).addClass("active").siblings().removeClass("active");
            $(".canGet>span").html(money*10);
        });
    });
    $(".btn_group>input").focus(function(){
        $(".canGet>span").html(0);
        $(".btn_group>.active").removeClass("active");
    });
    /**************支付方式******************/
    var topClick=0;
    $(".change_way>.way>li").each(function(){
        $(this).click(function(){
            if(new Date().getTime()-topClick>3000){
                topClick = new Date().getTime();
                if($(".btn_group>.active").html()) {
                var  count=$(".btn_group>.active").html();
                }else{
                    var count=$(".btn_group>input").val();
                    if(!((0<count)&&(count<=50000))){
                        alert("输入的金额不对...");
                        return false;
                    }
                }
                
                var form_data = {
                    paytype: $(this).attr("data-way"),
                    money: count,
                    paytoken: $("#paytoken").val(),
                    randnum: Math.random(),
                };  
                var vurl = $("#payform").attr("action");
                sendData(vurl,form_data,androidsucc,'',"POST","JSON");
            }
        });
    });
    
    //下单成功时调用android原生
    function androidsucc(result){
        if ('success'==result.state){
            window.android.callNativePay(result.payway, result.token);
        }else{
            alert(result.info);
        }
    }
    
    
    /*************立即冲值按钮******************/
    $(".instant_recharge>button").click(function(){
        var money=$(".btn_group>.active").html();
    });
    /*************充值金额输入******************/
    $(".btn_group>input").keyup(function(event){
        var input_value=$(this).val();
        var len=$(this).val().length;
        var str="";
        for(var k in input_value){
            var v=input_value[k];
            if((v>=0)&&(v<=9)){
                str+=v;
            }
        }
        if(str>50000){str=50000}
        $(this).val(str);
        $(".canGet>span").html(str*10);
    });