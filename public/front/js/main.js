jQuery(document).ready(function($){
	
	function height(){
	$('.full').height(function(){
   return $(window).height();
});	
	$('.full-one').height(function(){
   return $(window).height() * 0.8;
	});	
	}
	
height();
	$(window).resize(function() {	
	height();
	});
});

