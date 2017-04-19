// regformchk.js

var bValid_usr=false;
var bValid_pwd=false;
var bValid_pwdc=false;
var bValid_email=false;
var bValid_cc=false;
var bValid_rn=false;
var bValid_sfz=false;

var ajax_obj=null;
var mobilepsl_str=Array(
    Array("","gray"),
    Array("","blue"),
    Array("","orange"),
    Array("","green")
);

var mobileajax_domain=location.href;
mobileunspanajax_domain=document.domain;

    
function mobilecreateAjaxObj(){
    if(navigator.userAgent.indexOf("MSIE")!=-1)
        ajax_obj=new ActiveXObject("Microsoft.XMLHttp");
    else
        ajax_obj=new XMLHttpRequest();
}
     
function mobilefn_chgccimg(){
    $("#cc_ele").attr("src","Plugins/checkcode.php?"+Math.random());
}
    
function mobileShowMsg(IdStr,ErrNum,ErrStr){
    var ImgStr=new Array("","<img style=\"vertical-align: middle;\" src=\"../images/note_ok.gif\">","<img style=\"vertical-align: middle;\" src=\"../images/note_ok.gif\">")
    var ClrArr=new Array("#333","#339900","#413F3F");
    $("#"+IdStr).css({fontWeight:"",fontSize:"",color:'',display:""});
    $("#"+IdStr).html(ImgStr[ErrNum]+ErrStr);
}
    
function mobileChkUserName(){
    if($("#mobileusername").val()==""||!mobilephoneCheck($("#mobileusername").val())){
        mobileShowMsg("phone_info",0,"请输入正确手机号码。");
		jQuery("#phone_info").attr("class","error");
        bValid_usr=false;
        return false;
    }
    bValid_usr=false;
    createAjaxObj();
    ajax_obj.onreadystatechange=mobileChkUserName_callback;
    ajax_obj.open("GET","http://"+ajax_domain+"/?m=user&action=check&username="+ encodeURIComponent($("#mobileusername").val())+"&param=user",true);
    ajax_obj.send(null);
    //ShowMsg("unspan",0,"由6-16位字母和数字组成，不区分大小写");
}
    
function mobileChkUserName_callback(){
	var username = jQuery("#mobileusername").val();
    if(ajax_obj.readyState==4&&ajax_obj.status==200){
        var ret_str=ajax_obj.responseText;
        switch(ret_str){
            case "0":
               mobileShowMsg("phone_info",1,"欢迎您，"+username);
				jQuery("#phone_info").attr("class","ok");
				//jQuery("#phone_info").html("");
                bValid_usr=true;
                break;
            case "1":
                mobileShowMsg("phone_info",0,"此通行证账号已经被抢注。");
				jQuery("#phone_info").attr("class","error");
                bValid_usr=false;
                break;
            case "4":
                //ShowMsg("unspan",0,"这个用户已经注册，请更换一个!");
                //bValid_usr=false;
                //break;
            default:
                mobileShowMsg("phone_info",0,"网络错误!");
				jQuery("#phone_info").attr("class","error");
                bValid_usr=false;
        }
    }
}
    
function mobileChkPwd(){
    var pwd_len=$("#mobileusrpwd").val().length;
    //bValid_pwd=bValid_pwdc=false;
    if(pwd_len<6){
        mobileShowMsg("mobilepwdspan",0,"6个或更多字符! 要复杂些");
		jQuery("#mobilepwdspan").attr("class","error");
        fn_setpwdsl(0);
        return false;
    }
    var secure = checkPwdSecure(gvbi2("mobileusrpwd"));
    if(secure ==1){
    	mobileShowMsg("mobilepwdspan",1,"密码还可以更复杂。");
		jQuery("#mobilepwdspan").attr("class","ok");
    }else if(secure == 2){
    	mobileShowMsg("mobilepwdspan",1,"密码复杂度还可以。");
		jQuery("#mobilepwdspan").attr("class","ok");
    }else if(secure == 3){
    	mobileShowMsg("mobilepwdspan",1,"密码很完美！");
		jQuery("#mobilepwdspan").attr("class","ok");
    }
    //ShowMsg("pwdspan",1,"密码可用!");
    //bValid_pwd=true;
    if($("#mobileusrpwdc").val()!="")mobileChkPwdc();
    mobilefn_setpwdsl(secure);
    
    return true;
}

    
    
function mobilefn_setpwdsl(level){
    for(var psi=1;psi<=3;psi++){
        gebi("mobilepsl"+psi).style.background="#e0e0e0";
        setHtmlById("mobilepsl"+psi,"");
    }
    if(level==0){
        return;
    }
    gebi("mobilepsl"+level).style.background=psl_str[level][1];
    setHtmlById("mobilepsl"+level,psl_str[level][0]);
}
    
function mobilegebi(id_str){
    return document.getElementById(id_str);
}
    
function mobilegvbi2(id_str){
    return document.getElementById(id_str).value;
}
    
function mobilecheckPwdSecure(pwd_str){
    var tmp_secure_level=0;
    
    if(pwd_str.match(/[a-zA-Z]/)){
        tmp_secure_level++;
    }
    if(pwd_str.match(/[0-9]/)){
        tmp_secure_level++;
    }
    if(pwd_str.match(/[~!@#$%^&*()_+|{}:"<>?`=-\\\[\];',./]/)){
        tmp_secure_level++;
    }
    
    return tmp_secure_level;
}

function mobilesetHtmlById(id_str,HTML_val){
    gebi(id_str).innerHTML=HTML_val;
}

function mobileChkPwdc(){
    //bValid_pwdc=false;
    if($("#mobileusrpwdc").val()==""){
        mobileShowMsg("mobilepwdcspan",0,"再次输入密码，确保密码输入正确。");
		jQuery("#mobilepwdcspan").attr("class","error");
        return false;
    }
    
    if($("#mobileusrpwdc").val()==$("#mobileusrpwd").val()){
        mobileShowMsg("mobilepwdcspan",1,"密码确认无误。");
		jQuery("#mobilepwdcspan").attr("class","ok");
        return true;
    }
    mobileShowMsg("mobilepwdcspan",0,"两次输入的密码不一致。");
	jQuery("#mobilepwdcspan").attr("class","error");
    return false;
}

/*function ChkEmail(){
    bValid_email=false;
    var mail_str=$("#email").val();
    
    if(mail_str==""){
        ShowMsg("emailspan",0,"你的邮箱地址是什么？");
        return false;
    }
    
    if(mail_str.match(/^[-_.a-z0-9A-Z]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+([-_.][a-zA-Z0-9]+))+$/i)){///(^[a-zA-Z0-9_-]{1,})+@([a-zA-Z0-9]{1,})+\.([a-zA-Z]{2,})$/g)){
        ShowMsg("emailspan",1,"可以通过此邮箱修改密码。");
        createAjaxObj();
        ajax_obj.onreadystatechange=ChkEmail_callback;
        ajax_obj.open("GET","http://"+ajax_domain+"/?m=user&action=check&email="+$("#email").val()+"&param=email",true);
        ajax_obj.send(null);
    }
    ShowMsg("emailspan",0,"不像是有效的电子邮箱。");
}

function ChkEmail_callback(){
    if(ajax_obj.readyState==4&&ajax_obj.status==200){
        var ret_str=ajax_obj.responseText;
        switch(ret_str){
            case "1":
            case "0":
                ShowMsg("emailspan",1,"此邮箱可以注册!");
                bValid_email=true;
                break;
            /*case "1":
                ShowMsg("emailspan",0,"这个邮箱已经注册，请更换一个!");
                bValid_email=false;
                break;
            default:
                ShowMsg("emailspan",0,"网络错误!");
                bValid_email=false;
        }
    }
}*/

function mobileChkCode(){
    bValid_cc=false;
    var cc_str=$("#mobilecode").val();
    
    if(cc_str==""){
        mobileShowMsg("mobilecodespan",0,"请输入验证码");
		jQuery("#mobilecodespan").attr("class","error");
        return false;
    }
    
    mobileShowMsg("mobilecodespan",1,"");
    createAjaxObj();
    ajax_obj.onreadystatechange=mobileChkCode_callback;
    ajax_obj.open("GET","http://"+ajax_domain+"/?m=user&action=check&regcheckcode="+$("#mobilecode").val()+"&param=regmobilecode",true);
    ajax_obj.send(null);
    return bValid_cc;
}

function mobileChkCode_callback(){
    if(ajax_obj.readyState==4&&ajax_obj.status==200){
        var ret_str=ajax_obj.responseText;
		//alert(ret_str);
        switch(ret_str){
            case "0":
                mobileShowMsg("mobilecodespan",0,"你输入的验证码不正确。");
				jQuery("#mobilecodespan").attr("class","error");
                bValid_cc=false;
                break;
            case "1":
                mobileShowMsg("mobilecodespan",1,"验证码输入正确。");
				jQuery("#mobilecodespan").attr("class","ok");
                bValid_cc=true;
                break;
            default:
                mobileShowMsg("mobilecodespan",0,"网络错误!");
				jQuery("#mobilecodespan").attr("class","error");
                bValid_cc=false;
        }
    }
}

function ShowMorePpd(){
    var bmppd=$("#mppd").val()=="more"?true:false;
    $("#moreppd").css({display:bmppd?"none":"block"});
    bmppd=!bmppd;
    $("#mppd").val(bmppd?"more":"");
}

/*function mobileChkcn(obj){
    //obj.value=obj.value.replace(/[\u4e00-\u9fa5]/g,"");
    obj.value=obj.value.replace(/^(13[0-9]|15[0|1|3|6|7|8|9]|18[8|9|6])\d{8}$/,"");
}*/

function mobileChkrn(){
    bValid_rn=false;
    
    var rn_str=$("#mobilerealname").val();
    
    if(rn_str==""){
        mobileShowMsg("mobilernspan",0,"请输入您的真实姓名,如:张三");
		jQuery("#mobilernspan").attr("class","error");
        return false;
    }
    
    if(rn_str.match(/^[\u4e00-\u9fa5]{2,4}$/g)){
        mobileShowMsg("mobilernspan",1,"名字好棒。");
		jQuery("#mobilernspan").attr("class","ok");
        bValid_rn=true;
        return true;
    }
    mobileShowMsg("mobilernspan",0,"你需要输入一个汉语姓名，含2~4个汉字。");
	jQuery("#mobilernspan").attr("class","error");
    return false;
}

function mobileChkNO(){
    var str_NO=$("#mobilesfznum").val();
    bValid_sfz=false;
    
    if(str_NO==""){
        mobileShowMsg("mobilenospan",0,"请输入身份证号,如:440106198507131483");
		jQuery("#mobilenospan").attr("class","error");
        return false;
    }
    
    if(isIdCardNo(str_NO)){
        mobileShowMsg("mobilenospan",1,"身份证号码正确无误。");
		jQuery("#mobilenospan").attr("class","ok");
        /*
        var area = str_NO.substr(0,2);
        $('#area').val(area);
        if(!$('#area').val()){
            ShowMsg("nospan",0,"输入的身份证号没有对应地区，或者号码不符合规定!");
            bValid_sfz=false;
        }else{
            bValid_sfz=true;
        }*/
        bValid_sfz=true;
    }else{
        mobileShowMsg("mobilenospan",0,"好像输入了错误的身份证号码。");
		jQuery("#mobilenospan").attr("class","error");
        bValid_sfz=false;
    }
    return bValid_sfz;
}

function QuestionChg(){
    var qust_obj=$("#question_ele")[0];
    
    if(qust_obj.selectedIndex==0){
        mobileShowMsg("answerspan",1,"");
    }
    if(qust_obj.selectedIndex!=0&&$("#answer").val()==""){
        mobileShowMsg("answerspan",0,"请填写答案!");
    }
}

function ChkAnswer(){
    var answer_str=$("#answer").val();
    if(answer_str.length>0){
        mobileShowMsg("answerspan",1,"");
        return;
    }
    if(answer_str.length==0&&$("#question_ele")[0].selectedIndex!=0)
        ShowMsg("answerspan",0,"请填写答案!");
}

function mobileregister_submit(){
    if(!$("#mobileagree_protocol")[0].checked){
        alert("不接受要玩游戏平台通行证用户服务协议和相关的条款和条件者不可注册!");
        return false;
    }
    /*用户名验证*/
    var mobileunspanucheck = false;
    if($("#mobileusername").val()==""||$("#mobileusername").val().length<6){
		
        mobileShowMsg("phone_info",0,"请输入手机号码");
			jQuery("#phone_info").attr("class","error");
        ucheck = false;
    }else{
    	if(bValid_usr == true){
    		 var username = jQuery("#mobileusername").val();
    		 mobileShowMsg("mobileunspan",1,"欢迎您，"+username);
    		 mobileunspanucheck = true;
    	}else{
    		 mobileShowMsg("mobileunspan",0,"此通行证账号已经被抢注。");
    		 mobileunspanucheck = false;
    	}
    }
    /*密码验证*/
    var mobileunspanpwd = mobileChkPwd();
    var mobileunspanpwdc = mobileChkPwdc();
   /*邮箱验证*/
/*    var echeck = false;
    var mail_str=$("#email").val();
    if(mail_str==""){
        ShowMsg("emailspan",0,"你的电子邮箱地址是什么？");
        echeck = false;
    }else{
    	if(bValid_email == true){
    		ShowMsg("emailspan",1,"可以通过此邮箱修改密码。");
    		echeck = true;
    	}else{
    		ShowMsg("emailspan",0,"不像是有效的电子邮箱。");
    		echeck = false;
    	}
    }*/
    /*验证码验证*/
    var mobilecode = false;
    var cc_str=$("#mobilecode").val();
    if(cc_str==""){
        mobileShowMsg("mobilecodespan",0,"请输入验证码");
		jQuery("#mobilecodespan").attr("class","error");
        mobilecode = false;
    }else{
    	if(bValid_cc == true){
    		 mobileShowMsg("mobilecodespan",1,"验证码输入正确。");
    		 mobilecode = true;
    	}else{
    		mobileShowMsg("mobilecodespan",0,"你输入的验证码不正确，请换一张试试。");
    		mobilecode = false;
    	}
    	
    }
	
    /*身份证号码验证*/
    var mobileunspanno = mobileChkNO();
    /*真实姓名验证*/
    var mobileunspanrn = mobileChkrn();
    if(mobileunspanucheck&&mobileunspanpwd&&mobileunspanpwdc&&mobileunspanno&&mobileunspanrn&&mobilecode){
    	return true;
    }else{
    	return false;
    }
}

function mobileisIdCardNo(num)
{  
      num = num.toUpperCase(); 
     //身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X。  
      if (!(/(^\d{15}$)|(^\d{17}([0-9]|X)$)/.test(num)))  
      {
           //alert('输入的身份证号长度不对，或者号码不符合规定！\n15位号码应全为数字，18位号码末位可以为数字或X。');
          return false;
     }
    //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
    //下面分别分析出生日期和校验位
    var len, re;
    len = num.length;
    if (len == 15)
    {
        re = new RegExp(/^(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})$/);
        var arrSplit = num.match(re);

        //检查生日日期是否正确
        var dtmBirth = new Date('19' + arrSplit[2] + '/' + arrSplit[3] + '/' + arrSplit[4]);
        var bGoodDay;
        bGoodDay = (dtmBirth.getYear() == Number(arrSplit[2])) && ((dtmBirth.getMonth() + 1) == Number(arrSplit[3])) && (dtmBirth.getDate() == Number(arrSplit[4]));
        if (!bGoodDay){
            //alert('输入的身份证号里出生日期不对！');  
            return false;
        }else{
        //将15位身份证转成18位
        //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                  var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                   var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                   var nTemp = 0, i;  
                    num = num.substr(0, 6) + '19' + num.substr(6, num.length - 6);
                   for(i = 0; i < 17; i ++)
                  {
                        nTemp += num.substr(i, 1) * arrInt[i];
                   }
                   num += arrCh[nTemp % 11];  
                    return num;  
        }  
    }
    if (len == 18){
        re = new RegExp(/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/);
        var arrSplit = num.match(re);

        //检查生日日期是否正确
        var dtmBirth = new Date(arrSplit[2] + "/" + arrSplit[3] + "/" + arrSplit[4]);
        var bGoodDay;
        bGoodDay = (dtmBirth.getFullYear() == Number(arrSplit[2])) && ((dtmBirth.getMonth() + 1) == Number(arrSplit[3])) && (dtmBirth.getDate() == Number(arrSplit[4]));
        if (!bGoodDay)
        {
            //alert(dtmBirth.getYear());
            //alert(arrSplit[2]);
            //alert('输入的身份证号里出生日期不对！');
            return false;
        }else{
            //检验18位身份证的校验码是否正确。
            //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
            var valnum;
            var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            var nTemp = 0, i;
            for(i = 0; i < 17; i ++)
            {
            nTemp += num.substr(i, 1) * arrInt[i];
            }
            valnum = arrCh[nTemp % 11];
            if (valnum != num.substr(17, 1))
            {
                //alert('18位身份证的校验码不正确！应该为：' + valnum);
                return false;
            }
            return num;
        }
    }
    return false;
} 


//QQ验证
function check_QQ()
{
   var reg = /^[1-9]\d{4,8}$/;
   var qq_str=$("#qq").val();

   if(qq_str != "")
   {
    if (reg.test(qq_str))
    {   
		return true;    
    }
    else
    {  
		mobileShowMsg("qq",0,"QQ格式错误");
		jQuery("#qq").attr("class","error");
        return false;
    }  
   }
}