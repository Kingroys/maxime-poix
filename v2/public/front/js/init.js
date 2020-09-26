$(document).ready(function(){
	$('.isWriting').isWriting({
		'speed': 5
	});
});

(function($){
	$.fn.isWriting = function(options) {
	
		var defauts = {
			'speed': 20
		};
		var params = $.extend(defauts, options);
	   
		return this.each(function() {
			var self = $(this);
			var html = self.html();
			var i = 0;

            self.html('');
			var interval = setInterval(function() {
				if(i < html.length){
					if (html.charCodeAt(i) == 10) { // NEW LINE
						self.append('<br />');
					} else if (html.charCodeAt(i) == 9) { // TAB
						self.append('&nbsp;&nbsp;&nbsp;&nbsp;');
					} else {
						self.append(html[i]);
					}
					i++;
				}
				else{ 
					clearInterval(interval);
				}
			}, params.speed);
		});
	};
})(jQuery);