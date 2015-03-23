	menu_click_count = 0;
	var hh = $(window).height();
	hh=hh+'px';
	$("#layer1").css('height',hh);
		
	$("#btn_menu").tap(function(){
		menu_click_count++;
		var bool_ = menu_click_count%2;
		if (bool_==1){
			$("#layer1").addClass("move-right").addClass("disable");
		} else if (bool_==0){
			$("#layer1").removeClass("move-right");
			setTimeout(function(){$("#layer1").removeClass("disable");}, 350);
		}
	});
	
	$("#layer0").tap(function(){			
		var bool_ = menu_click_count%2;
		if (bool_==1){
		console.log(menu_click_count+'::'+(bool_));
		menu_click_count++;
			$("#layer1").removeClass("move-right");
			setTimeout(function(){$("#layer1").removeClass("disable");}, 350);
		} 
	});
		
