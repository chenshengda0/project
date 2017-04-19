$("#old_pwd").blur(function(){
    var old_pwd=$(this).val();
    if(old_pwd.trim()==""){
        showMsg(".error_msg","原密码不能为空");
    }else{
        $(".error_msg").html("");
    }
});

$("#new_pwd").blur(function(){
    var old_pwd=$("#old_pwd").val();
    var new_pwd=$(this).val();
    
    var regcellpwd = /^[0-9A-Za-z-`=\\\[\];',.\/~!@#$%^&*()_+|{}:"<>?]{6,16}$/g;
    if( false == regcellpwd.test(new_pwd)){
        showMsg(".error_msg",'密码必须由6-16位的数字、字母、符号组成');
        return false;
    }
    
    if(old_pwd == new_pwd){
        showMsg(".error_msg","新密码与原密码相等");  
        return false;        
    }else{
        $(".error_msg").html("");
    }
});

$("#confirm_pwd").blur(function(){
    var confirm_pwd=$(this).val();
    var new_pwd=$("#new_pwd").val();
    if(confirm_pwd.trim()==""){
        showMsg(".error_msg","确认密码不能为空");
        return false;
    }else{
        $(".error_msg").html("");
    }
    if(confirm_pwd != new_pwd){
        showMsg(".error_msg","确认密码与新密码不相等");    	
    }else{
       $(".error_msg").html("");
    }
});

$(".confim_change").click(function(){
    var old_pwd=$("#old_pwd").val();
    var new_pwd=$("#new_pwd").val();
    var confirm_pwd=$("#confirm_pwd").val();
    
    if(old_pwd.trim()==""){
        showMsg(".error_msg","原密码不能为空");
        return false;
    }
    
    var regcellpwd = /^[0-9A-Za-z-`=\\\[\];',.\/~!@#$%^&*()_+|{}:"<>?]{6,16}$/g;
    if( false == regcellpwd.test(new_pwd)){
        showMsg(".error_msg",'密码必须由6-16位的数字、字母、符号组成');
        return false;
    }
    
    if(old_pwd == new_pwd){
        showMsg(".error_msg","新密码与原密码相等");  
        return false;        
    }
    if(confirm_pwd != new_pwd){
        showMsg(".error_msg","两次密码不相等");  
        return false;        
    }
    
    uppwdtoken = $("#uppwdtoken").val();
    var form_data = {
        oldpwd: old_pwd,
        newpwd: new_pwd,
        verifypwd: confirm_pwd,
        action: uppwdtoken
    };
    var vurl = $("#ajaxform").attr("action");
    sendData(vurl,form_data,androidsucc)
})
    //网络请求成功时返回信息
    function androidsucc(result){
        if ('success'==result.state){
            window.location.href=result.url;
        }else{
            alert(result.info);
        }
    }


