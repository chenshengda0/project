// regformchk.js

var bValid_usr=false;
var bValid_pwd=false;
var bValid_pwdc=false;
var bValid_email=false;
var bValid_cc=false;
var bValid_rn=false;
var bValid_sfz=false;

var ajax_obj=null;
var psl_str=Array(
    Array("","gray"),
    Array("","blue"),
    Array("","orange"),
    Array("","green")
);

var ajax_domain=location.href;
ajax_domain=document.domain;


function createAjaxObj(){
    if(navigator.userAgent.indexOf("MSIE")!=-1)
        ajax_obj=new ActiveXObject("Microsoft.XMLHttp");
    else
        ajax_obj=new XMLHttpRequest();
}

function fn_chgccimg(){
    $("#cc_ele").attr("src","Plugins/checkcode.php?"+Math.random());
}

function ShowMsg(IdStr,ErrNum,ErrStr){
    var ImgStr=new Array("","<img style=\"vertical-align: middle;\" src=\"http://img.yaowan.com/Template/2010ex/images/note_ok.gif\">","<img style=\"vertical-align: middle;\" src=\"http://img.yaowan.com/Template/2010ex/images/note_ok.gif\">")
    var ClrArr=new Array("#333","#339900","#413F3F");
    $("#"+IdStr).css({fontWeight:"",fontSize:"",color:'',display:""});
    $("#"+IdStr).html(ImgStr[ErrNum]+ErrStr);
}

function ChkUserName(){
	var ab=/^(13[0-9]|15[0|1|3|6|7|8|9]|18[8|9|6])\d{8}$/;
	if(ab.test($("#username").val())){
		ShowMsg("unspan",0,"个性注册不能为手机号码");
		jQuery("#unspan").attr("class","error");
        bValid_usr=false;
        return false;
		}
    if($("#username").val()==""||$("#username").val().length<6){
        ShowMsg("unspan",0,"帐号由6至32位字母、数字或_.-@组成，以字母或数字开头。");
		jQuery("#unspan").attr("class","error");
        bValid_usr=false;
        return false;
    }
    bValid_usr=false;
    createAjaxObj();
    ajax_obj.onreadystatechange=ChkUserName_callback;
    ajax_obj.open("GET","http://"+ajax_domain+"/?m=user&action=check&username="+ encodeURIComponent($("#username").val())+"&param=user",true);
    ajax_obj.send(null);
    //ShowMsg("unspan",0,"由6-16位字母和数字组成，不区分大小写");
}

function ChkUserName_callback(){
	var username = jQuery("#username").val();
    if(ajax_obj.readyState==4&&ajax_obj.status==200){
        var ret_str=ajax_obj.responseText;
        switch(ret_str){
            case "0":
                ShowMsg("unspan",1,"欢迎您，"+username);
				jQuery("#unspan").attr("class","ok");
                bValid_usr=true;
                break;
            case "1":
                ShowMsg("unspan",0,"此通行证账号已经被抢注。");
				jQuery("#unspan").attr("class","error");
                bValid_usr=false;
                break;
            case "4":
                //ShowMsg("unspan",0,"这个用户已经注册，请更换一个!");
                //bValid_usr=false;
                //break;
            default:
                ShowMsg("unspan",0,"网络错误!");
                bValid_usr=false;
        }
    }
}

function ChkPwd(){
    
    var pwd_len=$("#usrpwd").val().length;
    //bValid_pwd=bValid_pwdc=false;
    if(pwd_len<6){
        ShowMsg("pwdspan",0,"6-16位的字符!建议使用大小写字母和数字混合");
		jQuery("#pwdspan").attr("class","error");
        fn_setpwdsl(0);
        return false;
    }
    var secure = checkPwdSecure(gvbi2("usrpwd"));
    if(secure ==1){
    	ShowMsg("pwdspan",1,"密码还可以更复杂。");
		jQuery("#pwdspan").attr("class","ok");
    }else if(secure == 2){
    	ShowMsg("pwdspan",1,"密码复杂度还可以。");
		jQuery("#pwdspan").attr("class","ok");
    }else if(secure == 3){
    	ShowMsg("pwdspan",1,"密码很完美！");
		jQuery("#pwdspan").attr("class","ok");
    }
    //ShowMsg("pwdspan",1,"密码可用!");
    //bValid_pwd=true;
    if($("#usrpwdc").val()!="")ChkPwdc();
    fn_setpwdsl(secure);

    return true;
}



function fn_setpwdsl(level){
    for(var psi=1;psi<=3;psi++){
        gebi("psl"+psi).style.background="#e0e0e0";
        setHtmlById("psl"+psi,"");
    }
    if(level==0){
        return;
    }
    gebi("psl"+level).style.background=psl_str[level][1];
    setHtmlById("psl"+level,psl_str[level][0]);
}

function gebi(id_str){
    return document.getElementById(id_str);
}

function gvbi2(id_str){
    return document.getElementById(id_str).value;
}

function checkPwdSecure(pwd_str){
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

function setHtmlById(id_str,HTML_val){
    gebi(id_str).innerHTML=HTML_val;
}

function ChkPwdc(){
    //bValid_pwdc=false;
    if($("#usrpwdc").val()==""){
        ShowMsg("pwdcspan",0,"再次输入密码，确保密码输入正确。");
		jQuery("#pwdcspan").attr("class","error");
        return false;
    }

    if($("#usrpwdc").val()==$("#usrpwd").val()){
        ShowMsg("pwdcspan",1,"密码确认无误。");
		jQuery("#pwdcspan").attr("class","ok");
        return true;
    }
    ShowMsg("pwdcspan",0,"两次输入的密码不一致。");
	jQuery("#pwdcspan").attr("class","error");
    return false;
}

function ChkEmail(){
    bValid_email=false;
    var mail_str=$("#email").val();

    if(mail_str==""){
        ShowMsg("emailspan",0,"你的电子邮箱地址是什么？");
		jQuery("#emailspan").attr("class","error");
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
				jQuery("#emailspan").attr("class","ok");
                bValid_email=true;
                break;
            /*case "1":
                ShowMsg("emailspan",0,"这个邮箱已经注册，请更换一个!");
                bValid_email=false;
                break;*/
            default:
                ShowMsg("emailspan",0,"网络错误!");
				jQuery("#emailspan").attr("class","error");
                bValid_email=false;
        }
    }
}

function ChkCode(){
    bValid_cc=false;
    var cc_str=$("#checkcode").val();

    if(cc_str==""){
        ShowMsg("codespan",0,"请输入验证码，看不清的话可以换一张。");
		jQuery("#codespan").attr("class","error");
        return false;
    }

    ShowMsg("codespan",1,"");
    createAjaxObj();
    ajax_obj.onreadystatechange=ChkCode_callback;
    ajax_obj.open("GET","http://"+ajax_domain+"/?m=user&action=check&code="+$("#checkcode").val()+"&param=code",true);
    ajax_obj.send(null);
    return bValid_cc;
}

function ChkCode_callback(){
    if(ajax_obj.readyState==4&&ajax_obj.status==200){
        var ret_str=ajax_obj.responseText;
        switch(ret_str){
            case "0":
                ShowMsg("codespan",0,"你输入的验证码不正确，请换一张试试。");
				jQuery("#codespan").attr("class","error");
                bValid_cc=false;
                break;
            case "1":
                ShowMsg("codespan",1,"验证码输入正确。");
				jQuery("#codespan").attr("class","ok");
                bValid_cc=true;
                break;
            default:
                ShowMsg("codespan",0,"网络错误!");
				jQuery("#codespan").attr("class","error");
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

function Chkcn(obj){
    //obj.value=obj.value.replace(/[\u4e00-\u9fa5]/g,"");
    obj.value=obj.value.replace(/[^a-z0-9A-Z_+]/g,"");
}

function Chkrn(){
    bValid_rn=false;

    var rn_str=$("#realname").val();

    if(rn_str==""){
        ShowMsg("rnspan",0,"请输入您的真实姓名,如:张三");
		jQuery("#rnspan").attr("class","error");
        return false;
    }

    if(rn_str.match(/^[\u4e00-\u9fa5]{2,4}$/g)){
        ShowMsg("rnspan",1,"名字好棒。");
		jQuery("#rnspan").attr("class","ok");
        bValid_rn=true;
        return true;
    }
    ShowMsg("rnspan",0,"你需要输入一个汉语姓名，含2~4个汉字。");
	jQuery("#rnspan").attr("class","error");
    return false;
}

function ChkNO(){
    var str_NO=$("#sfznum").val();
    bValid_sfz=false;

    if(str_NO==""){
        ShowMsg("nospan",0,"请输入身份证号,如:440106198507131483");
		jQuery("#nospan").attr("class","error");
        return false;
    }

    if(isIdCardNo(str_NO)){
        ShowMsg("nospan",1,"身份证号码正确无误。");
		jQuery("#nospan").attr("class","ok");
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
        ShowMsg("nospan",0,"好像输入了错误的身份证号码。");
		jQuery("#nospan").attr("class","error");
        bValid_sfz=false;
    }
    return bValid_sfz;
}

function QuestionChg(){
    var qust_obj=$("#question_ele")[0];

    if(qust_obj.selectedIndex==0){
        ShowMsg("answerspan",1,"");
    }
    if(qust_obj.selectedIndex!=0&&$("#answer").val()==""){
        ShowMsg("answerspan",0,"请填写答案!");
    }
}

function ChkAnswer(){
    var answer_str=$("#answer").val();
    if(answer_str.length>0){
        ShowMsg("answerspan",1,"");
        return;
    }
    if(answer_str.length==0&&$("#question_ele")[0].selectedIndex!=0)
        ShowMsg("answerspan",0,"请填写答案!");
}

function register_submit(){
    if(!$("#agree_protocol")[0].checked){
        alert("不接受要玩游戏平台通行证用户服务协议和相关的条款和条件者不可注册!");
        return false;
    }
    /*用户名验证*/
    var phone=/^(13[0-9]|15[0|1|3|6|7|8|9]|18[8|9|6])\d{8}$/;
	if(phone.test($("#username").val())){
		ShowMsg("unspan",0,"个性注册不能为手机号码");
		jQuery("#unspan").attr("class","error");
        return false;
		}
    if($("#username").val()==""||$("#username").val().length<6){
        ShowMsg("unspan",0,"6~16位的数字或字母作为通行证账号。");
		jQuery("#unspan").attr("class","error");
        ucheck = false;
    }else{
    	if(bValid_usr == true){
    		 var username = jQuery("#username").val();
    		 ShowMsg("unspan",1,"欢迎您，"+username);
    		 ucheck = true;
    	}else{
    		 ShowMsg("unspan",0,"此通行证账号已经被抢注。");
    		 ucheck = false;
    	}
    }
    /*密码验证*/
    var pwd = ChkPwd();
    var pwdc = ChkPwdc();
   /*邮箱验证*/
    var echeck = false;
    var mail_str=$("#email").val();
    if(mail_str==""){
        ShowMsg("emailspan",0,"你的电子邮箱地址是什么？");
		jQuery("#emailspan").attr("class","error");
        echeck = false;
    }else{
    	if(bValid_email == true){
    		ShowMsg("emailspan",1,"可以通过此邮箱修改密码。");
			jQuery("#emailspan").attr("class","ok");
    		echeck = true;
    	}else{
    		ShowMsg("emailspan",0,"不像是有效的电子邮箱。");
			jQuery("#emailspan").attr("class","error");
    		echeck = false;
    	}
    }
    /*验证码验证*/
    var code = false;
    var cc_str=$("#checkcode").val();
    if(cc_str==""){
        ShowMsg("codespan",0,"请输入验证码，看不清的话可以换一张。");
		jQuery("#codespan").attr("class","error");
        code = false;
    }else{
    	if(bValid_cc == true){
    		 ShowMsg("codespan",1,"验证码输入正确。");
    		 code = true;
    	}else{
    		ShowMsg("codespan",0,"你输入的验证码不正确，请换一张试试。");
    		code = false;
    	}

    }
    /*身份证号码验证*/
    var no = ChkNO();
    /*真实姓名验证*/
    var rn = Chkrn();
    if(ucheck&&pwd&&pwdc&&echeck&&code&&no&&rn){
    	return true;
    }else{
    	return false;
    }
}

function isIdCardNo(num)
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











