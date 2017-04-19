       //展开侧滑页面
		 mTouch('#icon_7724').on('tap',  function () {
			 this.style.display="none";
					 $(".cehua_page").animate({
					left:"0%"
					},100);
				
				})
		//关闭侧滑页面
		 mTouch('.cehua_close').on('tap',  function () {
			 document.getElementById("icon_7724").style.display="block";
					 $(".cehua_page").animate({
					left:"-100%"
					},100);
				})
	 
		//悬浮球
		var assistiveLeft, assistiveRight, timerid;
		var stickEdge = function (el) { 
			var left = parseInt(el.style.left) || 0,
				width = parseInt(el.offsetWidth) || 0,
				windowWith = (document.documentElement || document.body).offsetWidth;
			if (left > (windowWith - width) / 2) {
				left = windowWith - width+10 ;
			} else {
				left = -10;
			}
			el.style.transition = 'all .2s';
			el.style['transition'] = 'all .2s';
			el.style.left = left + 'px';
			timerid = setTimeout(function () {
				el.style.transition = 'all .5s';
				el.style['transition'] = 'all .5s';
				 
			}, 2000);
		};
		mTouch('#icon_7724').on('swipestart', function (e) {
			clearTimeout(timerid);
			e.stopPropagation();
			this.style.transition = 'none';
			this.style['transition'] = 'none';
			assistiveLeft = parseInt(this.style.left) || 0;
			assistiveTop = parseInt(this.style.top) || 0;
			return false;
		})
		.on('swiping', function (e) {
			e.stopPropagation();
			this.style.left =  assistiveLeft + e.mTouchEvent.moveX + 'px';
			this.style.top = assistiveTop + e.mTouchEvent.moveY + 'px';
		})
		.on('swipeend', function () {
			stickEdge(this);
		});
		
		(function(){
			var control = navigator.control || {};
			if (control.gesture) {
					control.gesture(false);
			}
			})();