/* 
 * 公用ＪＳ
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Comment
 */
function Common() {
    this.checkTel = function (pTel) { 
            var patrn = /^(1[3-9])\d{9}$/;
            if (!patrn.exec(pTel))
                return false;
            return true;
       
    }
    
    this.checkMail=function(pMail)
    {
         var patrn = /^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/;
          if (!patrn.exec(pMail))
                return false;
            return true;        
    }
    
    
    this.checkUsername = function (v) { 
    	 var partten = /^((\(\d{3}\))|(\d{3}\-))?13[0-9]\d{8}|15[0-9]\d{8}|18[0-9]\d{8}|14[0-9]\d{8}$/;
    		if (partten.test(v)){
    			if(v.length != 11){
    				return false;
    			}
    		}else{
    			 var regex=/^[0-9A-Za-z_]{6,15}$/;
    			 return regex.exec(v)
    		}
    		return true;
   
    }

}