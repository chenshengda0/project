<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="textml; charset=UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />
<title><?php echo ($title); ?></title>
<link rel="stylesheet" type="text/css" href="/public/float/css/float.css" />
</head>
<body>
<!--
<div class="modular">
    <ul>
        <li class="back"><a onclick="history.go(-1);"><img src="/public/float/images/goback.png"><span class="main_title"><?php echo ($title); ?></span></a></li>
        <li><a onclick="window.mgw_web_back.goToGame()">回到游戏</a></li> 
    </ul>
</div>-->

<div class="modular1">
         <div class="modular2"><img src="/public/float/images/1.png"></div>
         <div class="modular3"> 
                  <div>
                           <div><?php echo ($userdata["username"]); ?></div>
                           <!--<div>ID:<?php echo ($userdata["id"]); ?></div>-->
						   <div>平台币:<?php echo ((isset($ptb_sum) && ($ptb_sum !== ""))?($ptb_sum):'0'); ?>个</div>
                  </div>
                  <!--<div><span><?php echo ($yxb_cnt); ?> </span><span>张代金券</span></div>-->
         </div>
         <div class="modular4">
                   <!-- <div><a href="">隐藏悬浮 </a></div>-->
                  <!-- <div><a href=""> 切换账号</a></div>-->
         </div>
</div>

 <div class="personal1">
      <div class="personal2" style="border-top:solid 1px e6e6e6">
        <a href="<?php echo U('Float/User/uppwd');?>">
           <ul>          
               <li><img src="/public/float/images/edit.png"></li>
               <li>
                   <div>修改密码</div>
                </li>
               <li><img src="/public/float/images/forward.png"></li>
          </ul>    
        </a>
      </div>
        <div class="personal2">
        <a href="<?php echo U('Float/Binding/index');?>">
           <ul>          
               <li><img src="/public/float/images/phone.png"></li>
               <li>
                   <div>账户安全</div>
                </li>
                
               <li>
				<?php if(empty($userdata['email']) AND empty($userdata['mobile'])): ?><span class="personal3">有风险</span>
				<?php else: ?>
					<span class="personal3">已绑定</span><?php endif; ?>
               <img src="/public/float/images/forward.png"></li>
          </ul>    
        </a>
      </div>
       <div class="personal2">
        <a href="<?php echo U('Float/Help/index');?>">
           <ul>          
               <li><img src="/public/float/images/help.png"></li>
               <li>
                   <div>客服中心</div>
                </li>
               <li><img src="/public/float/images/forward.png"></li>
          </ul>    
        </a>
      </div>
  </div>


   <div class="personal1">
      <div class="personal2" style="border-top:solid 1px e6e6e6">
        <a href="<?php echo U('Float/Pay/pay');?>">
           <ul>          
               <li><img src="/public/float/images/money.png"></li>
               <li>
                   <div>消费记录</div>
                </li>
               <li><img src="/public/float/images/forward.png"></li>
          </ul>    
        </a>
      </div>
      <div class="personal2">
        <a href="<?php echo U('Float/Notice/index');?>">
           <ul>          
               <li><img src="/public/float/images/7.png"></li>
               <li>
                   <div>公告</div>
                </li>
               <li><img src="/public/float/images/forward.png"></li>
          </ul>    
        </a>
      </div>
        <!--<div class="personal2">
        <a href="<?php echo U('Float/Pay/charge');?>">
           <ul>          
               <li><img src="/public/float/images/charge.png"></li>
               <li>
                   <div>充值记录</div>
                </li>
               <li><span class="personal3"></span><img src="/public/float/images/forward.png"></li>
          </ul>    
        </a>
      </div>
       <div class="personal2">
        <a href="<?php echo U('Float/Pay/yxb', array('status'=>2));?>">
           <ul>          
               <li><img src="/public/float/images/7.png"></li>
               <li>
                   <div>代金券详情</div>
                </li>
               <li><img src="/public/float/images/forward.png"></li>
          </ul>    
        </a>
      </div>-->
  </div>
</body>
<script src="/public/float/js/main.js"></script>
</html>