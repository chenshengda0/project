// JavaScript Document
(function(){
	$.fn.scrollpic = function(o){
		o = $.extend({
						pause: 30,
						nstep: 1,
						ustep: 5,
						isH: true						
		}, o||{});
		
		return this.each(function(){
			var $box = $(".sp-box", this), $cont = $(".sp-cont", this), 
			$prev = $(".prev", this), $next = $(".next", this);
			$a = $cont.children("a"), len = $a.length;
			$cont.append($a.clone(true));
			var box = $box.get(0);
			
			var interval = null, cdir, a_dir = o.isH ? (['left', 'right']):(['top', 'bottom']),
			contDS = o.isH ? "width" : "height", aSize = o.isH ? $a.outerWidth(true) : $a.outerHeight(true), contS = len*aSize;
			$cont.css(contDS, 2*contS);
			
			$box.hover(function(){
				clearInterval(interval);
			}, function(){
				roll(cdir, o.nstep);
			});
			if($prev){
				$prev.hover(function(){
					roll(a_dir[1], o.ustep);
				}, function(){
					roll(a_dir[1], o.nstep);
				});
			}
			if($next){
				$next.hover(function(){
					roll(a_dir[0], o.ustep);
				}, function(){
					roll(a_dir[0], o.nstep);
				});
			}
			roll(a_dir[0], o.nstep);
			function roll(dir, step){
				clearInterval(interval);
				cdir = dir;
				interval = setInterval(function(){ _roll(cdir, step)}, o.pause);
			};
			function _roll(dir, step){
				switch(dir){
					case "left":
						(box.scrollLeft>=contS) ? (box.scrollLeft -= contS) : (box.scrollLeft += step);
						break;
					case "right":
						(box.scrollLeft<=0) ? (box.scrollLeft += contS) : (box.scrollLeft -= step);
						break;
					case "top":
						(box.scrollTop>=contS) ? (box.scrollTop -= contS) : (box.scrollTop += step);
						break;
					case "bottom":
						(box.scrollTop<=0) ? (box.scrollTop += contS) : (box.scrollTop -= step);
						break;
					default:
						return;
				}
			};
		});
	};
})(jQuery);