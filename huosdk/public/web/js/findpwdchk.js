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
        ShowMsg("pwdspan",0,"6-16位字符!建议使用字母和数字混合");
		jQuery("#pwdspan").attr("class","error");
        fn_setpwdsl(0);
        return false;
    }
    var secure = checkPwdSecure(gvbi2("usrpwd"));
    ShowMsg("pwdspan",1,"");
	jQuery("#pwdspan").attr("class","ok");
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










