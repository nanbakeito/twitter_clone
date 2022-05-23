$(document).ready(function(){
	$("#demo_menu li").hover(function() {
		$(this).children('ul').slideDown();
		
	}, function() {
		$(this).children('ul').hide();
	});
})