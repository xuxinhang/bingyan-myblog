module.exports = {
  setFontSize() {
    //动态改变根节点字体大小
		var docEle = document.documentElement,
	    evt = "onorientationchange" in window ? "orientationchange" : "resize",
	    fn = function() {
	        var width = docEle.clientWidth;
          var height = docEle.clientHeight;
          var scale = (width / height) / (750 / 1206);
	        width && (docEle.style.fontSize = width / (7.5 * scale) + "px");
	    };
		window.addEventListener(evt, fn, false);
		document.addEventListener("DOMContentLoaded", fn, false);
  },
  setBorderHeight() {
    var windowHeight = document.documentElement.clientHeight
    var border = document.getElementById('body-container')
    border.style.height = windowHeight * 0.89 + 'px'
  }
}
