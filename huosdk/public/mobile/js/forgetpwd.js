/*******输入框******/
$(".inputBox").focus(function(){
    $(".error_box").html("");
});
/********提交按钮******/
$("footer>.commit").click(function(){
    var num=$(".inputBox").val();
    if(num.trim()!==""){
        sendData("1.php",{"num":num},function(data){
            alert("提交成功");
        })
    }else{
        showMsg(".error_box","帐号不能为空...")
    }

});

$("#username").blur(function(){
    var username=$(this).val();    
    if(!checkUser(username)){
        showMsg(".error_box","请输入正确的用户名");  
        return false;        
    }else{
        $(".error_box").html("");
    }
});

//忘记密码
$(".confim_change>button").click(function(){
    var username=$("#username").val();
    var data={};
    var url=$("#ajaxUrl").val();
    var form_data = {
        username: username
    };    
    sendData(url,form_data,succ);
    function succ(result){
        if ('success'==result.state){
            window.location.href=result.url;
        }else{
            showMsg(".error_box",result.info);
        }
    }
});