    function fontRem (width) {
		var designW = width;
		var html = document.getElementsByTagName('html')[0];
		var winW = html.offsetWidth;
		if(winW < designW) {
			html.style.fontSize = (winW / designW) * 100 + 'px';
		} else {
			html.style.fontSize =  100 + 'px';
		}
	}
	/**
	 *
	 * @param {*Number} width 设计稿的宽度
	 */
	function rem (width) {
		fontRem(width);
		window.onresize = () => {
			fontRem(width)
		};
	}
	rem(750);