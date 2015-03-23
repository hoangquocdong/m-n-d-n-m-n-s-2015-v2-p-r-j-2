	menu_click_count = 0;
	var hh = $(window).height();
	hh=hh+'px';
	
	$("#layer1").css('height',hh);
	$("#toggle > li > div").tap(function () {
	//console.log("phee");
	if ($(this).hasClass("enable")) {
		
		console.log("phee");
		if (false == $(this).next().is(':visible')) {
			$('#toggle ul').slideUp(); 		
		}
		
		var $currIcon=$(this).find("span.the-btn > i")
		
		$("span.the-btn > i").not($currIcon).addClass('fa-plus').removeClass('fa-minus');
		
		$currIcon.toggleClass('fa-minus fa-plus');
		
		$(this).next().slideToggle();

		$("#toggle > li > div").removeClass("active");
		$(this).addClass('active');
		

	}
		myScroll.refresh();

	});

	$("#toggle li ul li").tap(function(){			
		//alert($(this).data('serial'));
		localStorage.current_meter_name = $(this).find('a').text();
		localStorage.current_meter_serial = $(this).data('serial');
		console.log(localStorage.current_meter_name+' == '+localStorage.current_meter_serial);
		//return false;
		window.location.assign('index_current_meter.html');
	});
	/*
	$("#toggle li ul li a").tap(function(){			
		//alert($(this).data('serial'));
		localStorage.current_meter_name = $(this).text();
		//alert (localStorage.current_meter_name);
		window.location.assign('index_current_meter.html');
	});
	*/
	$("#van_hanh").tap(function(){			
		//alert($(this).data('serial'));
		window.location.assign('index_current_meter.html');
		
	});
	$("#phu_tai").tap(function(){			
		//alert($(this).data('serial'));
		window.location.assign('index_profile_meter.html');
		
	});
	$("#chi_so_ngay").tap(function(){			
		//alert($(this).data('serial'));
		window.location.assign('index_day_current_meter.html');
		//$('#wrapper').load('index_h1_meter.html');
		
	});
	$("#chi_so_thang").tap(function(){			
		//alert($(this).data('serial'));
		window.location.assign('index_h1_meter.html');
		//$('#wrapper').load('index_h1_meter.html');
		
	});

	$("#btn_menu_right").tap(function(){
		window.location.assign('index_listmeter.html');
		
	});
	
	$("#turn_off").tap(function(){
		window.location.assign('../index.html');
		
	});
	$("#back").tap(function(){
		window.history.back();
	});
	$("#forward").tap(function(){
		window.history.forward();
	});
	$("#refresh").tap(function(){
		window.location.assign('index_listmeter.html');		
	});
	
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
	
	