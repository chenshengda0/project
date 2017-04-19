$("#new_pwd").blur(function(){
    var new_pwd=$("#this").val();    
    var regcellpwd = /^[0-9A-Za-z-`=\\\[\];',.\/~!@#$%^&*()_+|{}:"<>?]{6,16}$/g;
    if( false == regcellpwd.test(new_pwd)){
        showMsg(".error_box",'密码必须由6-16位的数字、字母、符号组成');
    }else{
       $(".error_box").html("");
    }
});

$("#confirm_pwd").blur(function(){
    var confirm_pwd=$(this).val();
    var new_pwd=$("#new_pwd").val();
    if(confirm_pwd.trim()==""){
        showMsg(".error_box","确认密码不能为空");
        return false;
    }else{
        $(".error_box").html("");
    }
    if(confirm_pwd != new_pwd){
        showMsg(".error_box","确认密码与新密码不相等");    	
    }else{
       $(".error_box").html("");
    }
});

$(".confim_change").click(function(){
    var new_pwd=$("#new_pwd").val();
    var confirm_pwd=$("#confirm_pwd").val();
        
    var regcellpwd = /^[0-9A-Za-z-`=\\\[\];',.\/~!@#$%^&*()_+|{}:"<>?]{6,16}$/g;
    if( false == regcellpwd.test(new_pwd)){
        showMsg(".error_box",'密码必须由6-16位的数字、字母、符号组成');
        return false;
    }
    
    if(confirm_pwd != new_pwd){
        showMsg(".error_box","两次密码不相等");  
        return false;        
    }
    
    uppwdtoken = $("#uppwdtoken").val();
    var form_data = {
        newpwd: new_pwd,
        verifypwd: confirm_pwd,
        action: uppwdtoken
    };
    var vurl = $("#ajaxurl").val();
    sendData(vurl,form_data,androidsucc)
})

//网络请求成功时返回信息
function androidsucc(result){
    if ('success'==result.state){
        window.location.href=result.url;
    }else{
        showMsg(".error_box",result.info); 
    }
}


