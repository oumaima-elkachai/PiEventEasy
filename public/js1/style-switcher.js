$(function(){
	
	$('head').append('<link rel="stylesheet" href="assets/css/colors/style-switcher.css" type="text/css" />');
	// $('head').append('<script type="text/javascript" src="assets/css/colors/jquery.cookie.min.js"></script>');
		
	//Style container
	var switcher = $('<div class="switcher"><span class="switch"><i class="fas fa-cog fa-spin"></i></span><h4>Color Options</h4><hr><div class="s-color"><a href="#" data-code="default"></a><a href="#" data-code="color-1"></a><a href="#" data-code="color-2"></a><a href="#" data-code="color-3"></a><a href="#" data-code="color-4"></a><a href="#" data-code="color-5"></a></div><div class="layout-btn-group"><ul><li><button id="box-layout">Box width</button></li><li><button id="full-width">full width</button></li></ul></div></div>');
	
	$('body').append(switcher);
			
		// Display after some time 
	$(".switcher .switch-h").delay("1500").fadeIn(3000);
		
	setTimeout(function(){ $('.switcher .switch-h').fadeOut() }, 10000);	

	$('.switch').click(function() {
		var $slidebox=$('.switcher');
		// var $s_hide=1;
		if($slidebox.css('left')=="-251px"){
		  $slidebox.animate({left:0},300);
		}
		else{
		  $slidebox.animate({left:-251},300);
		  // $.cookie('switch', $s_hide);
		}
	});   


	// box layout - start
	// ==================================================
	$("#box-layout").bind("click", function() {
		$("body").addClass('box-layout');
	});
	$("#full-width").bind("click", function() {
		$("body").removeClass('box-layout');
	});
	// box layout - end
	// ================================================== 
	
	
	// Color Changer
	// By Cookie
	/*if($.cookie('mikiColor')!=null){
		var color_code = $.cookie('mikiColor');
		$('link[id="color_theme"]').attr('href', 'assets/css/colors/'+color_code+'.css');
	}*/
	// By click
	$('.s-color a').click(function(e){
		e.preventDefault();
		var color_code = $(this).attr('data-code');
		$('link[id="color_theme"]').attr('href', 'assets/css/colors/'+color_code+'.css');
		// $.cookie('mikiColor', color_code);
	});
	
	
});