//带更多切换菜单
function setTab(name,cursel,n){
	for(i=1;i<=n;i++){
		var menu=document.getElementById(name+i);
		var con=document.getElementById("con_"+name+"_"+i);
		var more=document.getElementById("more_"+name+"_"+i);
		menu.className=i==cursel?"hover":"";
		con.style.display=i==cursel?"block":"none";
		more.style.display=i==cursel?"block":"none";
	}
}
//不带更多切换菜单
function setTab1(name,cursel,n){
	for(i=1;i<=n;i++){
		var menu=document.getElementById(name+i);
		var con=document.getElementById("con_"+name+"_"+i);
		menu.className=i==cursel?"hover":"";
		con.style.display=i==cursel?"block":"none";
	}
}
//选择器
function $a(id,tag){var re=(id&&typeof id!="string")?id:document.getElementById(id);if(!tag){return re;}else{return re.getElementsByTagName(tag);}}
//标签切换效果[标题框子元素("id/li"),内容框子元素("id/li"),事件(mouseover/click),默认显示第几条(-1表示在鼠标移出全部隐藏,仅在事件mouseover有效),轮播时间(1秒=1000)]
function SwitchTag(tit,box,s,show,time)
{
	var t=tit.split('/'),b=box.split("/"),ts=$a(t[0],t[1]),bs=$a(b[0],b[1]),s=s||"onmouseover",now=show=show||0,c;
	for(var i=0;i<ts.length;i++){ts[i].old=ts[i].className.replace("show","");bs[i].old=bs[i].className.replace("show","");reg(i);}
	function init(){for(var i=0;i<ts.length;i++){ts[i].className=ts[i].old;bs[i].className=bs[i].old;};if(now!=-1){ts[now].className+=(t[2]||"")+" show";bs[now].className+=(b[2]||"")+" show";}}
	function reg(i){ts[i][s]=function(){clearInterval(c);now=i;init();}
	if(show!=-1&&time){bs[i].onmouseover=function(){clearInterval(c);};bs[i].onmouseout=function(){go();};ts[i].onmouseout=function(){go();}}
	if(show==-1&&s=="onmouseover"){ts[i].onmouseout=function(){now=-1;init();}}}
	function go(){c=setInterval(function(){(now<ts.length-1)?now++:now=0;init();},time);}
	if(show!=-1&&time){go();};init();
}
function addImg(url){var img=new Image();img.src=url;return img;}
//焦点滚动图
function FocusImg(time,foc,fbimg)
{
	var beg=$a($a(fbimg,"dd")[0],"div")[0];beg.check=true;
	var au=$a(foc,"a"),now=1,tm;var bimgs=$a(foc,"img");var vimg=new Image();vimg.src=bimgs[0].src;
	var ba=$a($a(fbimg,"dt")[0],"a")[0];ba.href=au[0].href;ba.onfocus=function(){this.blur()};
	var bt=$a(fbimg,"dt")[0];bt.appendChild(vimg);
	var bi=$a(bt,"img")[1];bi.alt=bimgs[0].alt;
	var bp=$a($a(fbimg,"dd")[0],"ul")[0];
	var len=au.length;for(var i=0;i<len;i++){bp.innerHTML+="<li>"+(i+1)+"</li>";}
	var bps=$a(bp,"li");bps[0].className="show";function pfn(i){bps[i].onclick=function(){go(i);if(beg.check){clearInterval(tm);init();}else{clearInterval(tm)}}}for(var i=0;i<len;i++){pfn(i);}
	function xunhuan(){if(/*@cc_on!@*/false){bi.style.filter="progid:DXImageTransform.Microsoft.Wipe(GradientSize=1.0,motion=forward)";bi.filters[0].Apply();bi.filters[0].Play(duration=1);}
	ba.href=au[now].href;bi.src=bimgs[now].src;bi.alt=bimgs[now].alt;for(var i=0;i<len;i++){bps[i].className="";}bps[now].className="show";(now<len-1)?now++:now=0;}
	function init(){tm=setInterval(xunhuan,time);};function go(n){clearInterval(tm);now=n;xunhuan();init();}init();
	beg.onclick=function(){if(beg.check){beg.check=false;clearInterval(tm);beg.innerHTML="PLAY";}else{beg.check=true;clearInterval(tm);xunhuan();init();beg.innerHTML="STOP";}};
}
//标签切换效果[标题框子元素("id/li"),内容框子元素("id/li"),事件(mouseover/click),默认显示第几条(-1表示在鼠标移出全部隐藏,仅在事件mouseover有效),轮播时间(1秒=1000)]
function SwitchTag(tit,box,s,show,time)
{
	var t=tit.split('/'),b=box.split("/"),ts=$a(t[0],t[1]),bs=$a(b[0],b[1]),s=s||"onmouseover",now=show=show||0,c;
	for(var i=0;i<ts.length;i++){ts[i].old=ts[i].className.replace("show","");bs[i].old=bs[i].className.replace("show","");reg(i);}
	function init(){for(var i=0;i<ts.length;i++){ts[i].className=ts[i].old;bs[i].className=bs[i].old;};if(now!=-1){ts[now].className+=(t[2]||"")+" show";bs[now].className+=(b[2]||"")+" show";}}
	function reg(i){ts[i][s]=function(){clearInterval(c);now=i;init();}
	if(show!=-1&&time){bs[i].onmouseover=function(){clearInterval(c);};bs[i].onmouseout=function(){go();};ts[i].onmouseout=function(){go();}}
	if(show==-1&&s=="onmouseover"){ts[i].onmouseout=function(){now=-1;init();}}}
	function go(){c=setInterval(function(){(now<ts.length-1)?now++:now=0;init();},time);}
	if(show!=-1&&time){go();};init();
}
//首页下拉菜单
function ShowList(id,vas)
{
	var zj=$a(id), mb=$a(id,"dt")[0],xs=$a(id,"dd")[0],sj=$a(xs,"li");if(vas!=null){var va=$a(vas);}
	mb.onclick=function(){xs.className="show";};
	zj.onmouseout=function(){xs.className="";};
	xs.onmouseover=function(){xs.className="show";};
	xs.onclick=function(){xs.className="";}
	for(var i=0;i<sj.length;i++){reg(i);}
	function reg(i)
	{
		sj[i]["onmouseover"]=function(){sj[i].className="show";if(vas){va.value=mb.innerHTML= sj[i].innerHTML;}else{mb.innerHTML=$a(sj[i],"a")[0].innerHTML;}}
		sj[i]["onmouseout"]=function(){sj[i].className="";};
	}
}
	//滚动/切屏效果，[id,子容器/孙容器,方向,速度,上按钮,下按钮,分页切换时间,每次切屏的条数]
function HtmlMove(id,tag,path,rate,upbt,downbt,pgtime,lis)
{
	var mous=false,o=$a(id);o.onmouseover=function(){mous=true;};o.onmouseout=function(){mous=false;};
	var fg=tag.split('/'),as=$a(o,fg[1]),fx=(path=="scrollRight"||path=="scrollLeft")?"scrollLeft":"scrollTop";
	var ow=fx=="scrollTop"?as[0].offsetHeight:as[0].offsetWidth,pw=fx=="scrollTop"?o.offsetHeight:o.offsetWidth;
	var pgli=lis||Math.floor((pw+ow/2)/ow),pg=Math.floor((as.length+(pgli-1))/pgli),pgmx=ow*pgli,now=0,mx,d;
	var os=$a(o,fg[0])[0];os.innerHTML+=os.innerHTML;d=setInterval(function(){go_to((path=="scrollTop"||path=="scrollLeft")?true:false);},pgtime);
	if(upbt)
	{
		var ups=$a(upbt),uimg=addImg(ups.src),uimg1=addImg(ups.src.replace(/(.*)(\.\w{3})/,"$1_$2"));
		ups.onmouseover=function(){this.src=uimg1.src;};ups.onmouseout=function(){this.src=uimg.src;}
		$a(upbt).onmousedown=function(){clearInterval(d);go_to(true);d=setInterval(function(){go_to(true);},pgtime);}
	}
	if(downbt)
	{
		var down=$a(downbt),dimg=addImg(down.src),dimg1=addImg(down.src.replace(/(.*)(\.\w{3})/,"$1_$2"));
		down.onmouseover=function(){this.src=dimg1.src;};down.onmouseout=function(){this.src=dimg.src;}
		down.onmousedown=function(){clearInterval(d);go_to(false);d=setInterval(function(){go_to(false);},pgtime);}
	}
	function go_to(fxs){if(mous){return;};var ex;
	if(fxs){if(now<pg){now++;}else{now=1;o[fx]=0;}mx=now*pgmx;ex=setInterval(function(){(o[fx]+rate<mx)?(o[fx]+=rate):o[fx]=mx;if(o[fx]==mx){clearInterval(ex);}},10);}
	else{if(now>0){now--;}else{now=pg-1;o[fx]=pg*pgmx;}mx=now*pgmx;ex=setInterval(function(){(o[fx]-rate>mx)?(o[fx]-=rate):o[fx]=mx;if(o[fx]==mx){clearInterval(ex);}},10);}}
}
//切换图
function clickMove(list,show,upbt,dobt)
{
	var as=$a(list),ss=$a(show,"dl"),li=$a(as,"li"),h=75,ubt=$a(upbt),dobt=$a(dobt),now=1;for(var i=0;i<6;i++){reg(i);}
	function reg(i){li[i].onclick=function(){now=i;init();}}
	dobt.onclick=function(){if(now<5){now++;}else{now=0;}init();};
	ubt.onclick=function(){if(now>0){now--;}else{now=5;}init();}
	function init(){for(var i=0;i<ss.length;i++){li[i].className="";ss[i].className="";}
	li[now].className="show";ss[now].className="show";if(now==5){as["scrollTop"]=3*h;}else{as["scrollTop"]=(now-1)*h;}};init();
	var uimg=addImg(ubt.src),uimg1=addImg(ubt.src.replace(/(.*)(\.\w{3})/,"$1_$2"));
	ubt.onmouseover=function(){this.src=uimg1.src;};ubt.onmouseout=function(){this.src=uimg.src;}
	var dimg=addImg(dobt.src),dimg1=addImg(dobt.src.replace(/(.*)(\.\w{3})/,"$1_$2"));
	dobt.onmouseover=function(){this.src=dimg1.src;};dobt.onmouseout=function(){this.src=dimg.src;}
}

//点击框文字消失
function clickInput(id,str){var o=$a(id);o.value=str;o.onclick=function(){this.focus();if(this.value==str){this.value="";}};o.onmouseout=function(){if(this.value==""){this.value=str;}}}

//导航图片
function SwitchImg(id)
{
	var o=$a(id,"img");
	for (var i=0;i<o.length ;i++)
	{
		o[i].sa=addImg(o[i].src);
		o[i].sb=addImg(o[i].src.replace(/(.*)(\.\w{3})/,"$1_$2"));
		o[i].onmouseover=function(){this.src=this.sb.src;}
		o[i].onmouseout=function(){this.src=this.sa.src;}
	}
}

function HtmlTime(onid,id,today,tdays)
{
	var spe=false;
	var todays=new Date(today);
	var oy=todays.getFullYear();
	var om=todays.getMonth()+1;
	var od=todays.getDate();
	var onids=$a(onid);
	var y=oy,m=om;
	var o=$a(id);
	onids.innerHTML=om+"<span>月</span>"+od+"<span>日</sapn>";
	onids.onclick=function(){if(spe){spe=false;o.style.display="none";}else{spe=true;o.style.display="block";}}
	o.onmouseover=function(){spe=true;o.style.display="block";}
	o.onmouseout=function(){spe=false;o.style.display="none";}
	o.innerHTML="<dt><table><tr><td width='20'><b onmouseover=this.className='show' onmouseout=this.className=''><</b></td><td align=center></td><td width='20' align=right><b onmouseover=this.className='show' onmouseout=this.className=''>></b></td></tr></table></dt><dd><div><li>Sun</li><li>2014新测试延续资格查询</li><li>Tue</li><li>Wed</li><li>Thu</li><li>Fri</li><li>Sat</li></div><ul></ul></dd>";
	var p=$a(o,"b");
	var bd=$a(o,"ul")[0];
	var vsn=$a(o,"td")[1];
	p[0].onclick=function(){ms(false);}
	p[1].onclick=function(){ms(true);}
	function getdays(){return m==2?(y%4||!(y%100)&&y%400?28:29):(/4|6|9|11/.test(m)?30:31);}//获取某年某月的天数
	function ms(bo)
	{
		if(bo){if(m<12){m++;}else{m=1;y++;};if(m>om||y>oy){p[1].style.display="none";};p[0].style.display="block";}
		else{if(m>1){m--;}else{m=12;y--;};if(m<om||y<oy){p[0].style.display="none";};p[1].style.display="block";}up();
	}
	function up()
	{
		var t1=Array(new Date(y+"/"+m+"/1").getDay()+1).join('<li></li>');
		var t2=Array(getdays());
		for(var i=0;i<t2.length;i++)
		{
			var te=y+"/"+m+"/"+(i+1);
			for(var n=0;n<tdays.length;n++){if(te==tdays[n][0]){t2[i]="<li><a href='"+tdays[n][2]+"' title='"+tdays[n][1]+"' class='"+tdays[n][3]+"'>"+(i+1)+"</a></li>";break;}else{t2[i]="<li>"+(i+1)+"</li>";}}
		}
		var t3=Array(43-(t1.length/9+t2.length)).join("<li></li>");
		bd.innerHTML=[].concat(t1).concat(t2).concat(t3).join("");
		vsn.innerHTML=y+" &nbsp; "+((m.toString().length==1)?"0":"")+m;
	}up();
}

function movec(tone,ttwo,tthr,tfour)//点击移动
{
	var o=$a("bd1lfimg");
	var oli=$a("bd1lfimg","dl");//所有LI列表
    var oliw=oli[0].offsetWidth;//每次移动的宽度
	var ow=o.offsetWidth-2;
	var dnow;//=oli.length-3;//当前位置
	var day=(new Date()).getDay();
	switch(day)
	{
		case 1:dnow=1;break;
		case 2:dnow=0;break;
		case 3:dnow=2;break;
		case 4:dnow=3;break;
		case 5:dnow=0;break;
		case 6:dnow=0;break;
		case 0:dnow=0;break;
	}
	var olf=oliw-(ow-oliw+10)/2;
		o["scrollLeft"]=olf+(dnow*oliw);
	var rqbd=$a("bd1lfsj","ul")[0];
	var extime;
	var rqnr=["",tone,ttwo,tthr,tfour,"","","",""];

	for(var i=1;i<oli.length-1;i++){rqbd.innerHTML+="<li>"+rqnr[i]+"</li>";}

	var rq=$a("bd1lfsj","li");
	for(var i=0;i<rq.length;i++){reg(i);};
	oli[dnow+1].className=rq[dnow].className="show";
	var wwww=setInterval(uu,3000);

	function reg(i){rq[i].onclick=function(){oli[dnow+1].className=rq[dnow].className="";dnow=i;oli[dnow+1].className=rq[dnow].className="show";mv();}}
	function mv(){clearInterval(extime);clearInterval(wwww);extime=setInterval(bc,15);wwww=setInterval(uu,3000);}
	function bc()
	{
		var ns=((dnow*oliw+olf)-o["scrollLeft"]);
		var v=ns>0?Math.ceil(ns/10):Math.floor(ns/10);
		o["scrollLeft"]+=v;if(v==0){clearInterval(extime);oli[dnow+1].className=rq[dnow].className="show";v=null;}
	}
	function uu()
	{
		if(dnow<oli.length-3)
		{
			oli[dnow+1].className=rq[dnow].className="";
			dnow++;
			oli[dnow+1].className=rq[dnow].className="show";
		}
		else{oli[dnow+1].className=rq[dnow].className="";dnow=0;oli[dnow+1].className=rq[dnow].className="show";}
		mv();
	}
}

function bd1rvs(){var o=$a("bd1rva"), oli=$a(o,"li"),sli=$a("bd1rvb","dl");o.className="bd1rvav0";sli[0].className="show";for(var i=0;i<oli.length;i++){reg(i);}function reg(i){oli[i].onmouseover=function(){o.className="bd1rvav"+i;for(var x=0;x<oli.length;x++){sli[x].className="";}sli[i].className="show";}}}
