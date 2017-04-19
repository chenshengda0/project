function jsAuto(instanceName,objID)
{
	this._msg = [];
	this._x = null;
	this._o = document.getElementById( objID );
	if (!this._o) return;
	this._f = null;
	this._i = instanceName;
	this._r = null;
	this._c = 0;
	this._s = false;
	this._v = null;
	this._o.style.visibility = "hidden";
	this._o.style.position = "absolute";
	this._o.style.zIndex = "9999";
	this._o.style.overflow = "auto";
	this._o.style.height = "212";
	this._o.style.width = "260";
	return this;
};
jsAuto.prototype.directionKey=function() { with (this)
{
	var e = _e.keyCode ? _e.keyCode : _e.which;
	var l = _o.childNodes.length;
	(_c>l-1 || _c<0) ? _s=false : "";
	if( e==40 && _s )
	{
		_o.childNodes[_c].className="mouseout";
		(_c >= l-1) ? _c=0 : _c ++;
		_o.childNodes[_c].className="mouseover";
	}
	if( e==38 && _s )
	{
		_o.childNodes[_c].className="mouseout";
		_c--<=0 ? _c = _o.childNodes.length-1 : "";
		_o.childNodes[_c].className="mouseover";
	}
	/*if( e==13 )
	{
		if(_o.childNodes[_c] && _o.style.visibility=="visible")
		{
			var tmp = _x[_c].split("-");
			//_r.value = tmp[1];
			_o.style.visibility = "hidden";
			var msg_value_01 = tmp[1];
			msg_value_01 = msg_value_01.replace("<strong>","");
			msg_value_01 = msg_value_01.replace("</strong>","");
			_r.value = msg_value_01;
			_o.style.visibility = "hidden";
			
			var msg_value_00 = tmp[0];
			msg_value_00 = msg_value_00.replace("<strong>","");
			msg_value_00 = msg_value_00.replace("</strong>","");
			
			//document.getElementById("fundcode").value = msg_value_00;
			//window.location.href = '/simuchanpin-'+msgtmp[1]+'/';
			//document.getElementById("SubmitChoose").href = 'index.php?m=manager&c=index&a=show&Id='+msg_value_00;
		}
	}*/
	if( !_s )
	{
		_c = 0;
		_o.childNodes[_c].className="mouseover";
		_s = true;
	}
}};
// mouseEvent.
jsAuto.prototype.domouseover=function(obj) { with (this)
{
	_o.childNodes[_c].className = "mouseover";
	_c = 0;
	obj.tagName=="DIV" ? obj.className="mouseover" : obj.parentElement.className="mouseout";
}};
jsAuto.prototype.domouseout=function(obj)
{	
	obj.tagName=="DIV" ? obj.className="mouseout" : obj.parentElement.className="mouseover";
};
jsAuto.prototype.doclick=function(msg) { with (this)
{
	if(_r)
	{
		var msgtmp = msg.split("-");
		var msg_value_01 = msgtmp[1];
		msg_value_01 = msg_value_01.replace("<strong>","");
		msg_value_01 = msg_value_01.replace("</strong>","");
		_r.value = msg_value_01;
		_o.style.visibility = "hidden";
		
		var msg_value_00 = msgtmp[0];
		msg_value_00 = msg_value_00.replace("<strong>","");
		msg_value_00 = msg_value_00.replace("</strong>","");
		
		document.getElementById("productname").value = msg_value_01;
		document.getElementById("productid").value = msg_value_00;

		//document.getElementById("fundcode").value = msg_value_00;
		//window.location.href = '/simuchanpin-'+msgtmp[1]+'/';
		//document.getElementById("SubmitChoose").href = 'index.php?m=manager&c=index&a=show&Id='+msg_value_00;
	}
	else
	{
		alert("javascript autocomplete ERROR :\n\n can not get return object.");
		return;
	}
}};
// object method;
jsAuto.prototype.item=function(msg)
{
	if( msg.indexOf("|")>0)
	{
		var arrMsg=msg.split("|");
		for(var i=0; i<arrMsg.length; i++)
		//for(var i=0; i<10; i++)
		{
			arrMsg[i] ? this._msg.push(arrMsg[i].replace("^","-").replace("^","-")) : "";
		}
	}
	else
	{
		this._msg.push(msg);
	}
	//this._msg.sort();
};
jsAuto.prototype.append=function(msg) { with (this)
{
	_i ? "" : _i = eval(_i);
	_x.push(msg);
	var div = document.createElement("div");
	//bind event to object.
	div.onmouseover = function(){_i.domouseover(this)};
	div.onmouseout = function(){_i.domouseout(this)};
	div.onclick = function(){_i.doclick(msg)};
	var re  = new RegExp("(" + _v + ")","i");
	div.style.lineHeight="26px";
	div.className = "mouseout";
	if (_v){
		msg = msg.replace(re , "<strong>$1</strong>");
		var msg2 = msg.split("-");
		div.innerHTML = "<p><span class='i_Id'>"+msg2[0]+"</span><span class='i_name'>"+msg2[1]+"</span></p>";
	}
	div.style.fontFamily = "verdana";
	_o.appendChild(div);
	
	
}};
jsAuto.prototype.display=function() { with(this)
{
	if(_f&&_v!="")
	{
		_o.style.left = _r.offsetLeft;
		_o.style.width = _r.offsetWidth;
		_o.style.top = _r.offsetTop + _r.offsetHeight;
		_o.style.visibility = "visible";
	}
	else
	{
		_o.style.visibility="hidden";
	}
}};
jsAuto.prototype.handleEvent=function(fValue,fID,event) { with (this)
{
	var re;
	_e = event;
	var e = _e.keyCode ? _e.keyCode : _e.which;
	_x = [];
	_f = false;
	_r = document.getElementById( fID );
	fValue = fValue.replace("^","");
	_v = fValue;
	_i = eval(_i);
	re = new RegExp("^(.*?)" + fValue + "(.*?)", "i");
	_o.innerHTML="";
	var jj = 0;
	for(var i=0; i<_msg.length; i++)
	{
		if(re.test(_msg[i]))
		{
			_i.append(_msg[i]);
			_f = true;
			jj++;
		}
		if(jj>10){
			break;
		}
	}
	_i ? _i.display() : alert("can not get instance");
	if(_f)
	{
		if((e==38 || e==40 || e==13))
		{
			_o.childNodes[_c].className = "mouseout";
			_i.directionKey();
		}
		else
		{
			_c=0;
			_o.childNodes[_c].className = "mouseover";
			_s=true;
		}
	}
}};
window.onerror=new Function("return true;");