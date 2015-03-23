	menu_click_count = 0;
	var hh = $(window).height();
	hh=hh+'px';
	$("#layer1").css('height',hh);
	ajax_img_loading('img/loading_c.gif');
	$.ajax ({
				url:'http://mdms.npc.com.vn/mobile/server/listmeter.php', 
				type: 'GET',
				data: {usergroup:localStorage.usergroup, id_investor:localStorage.id_investor, id_pwc:localStorage.id_pwc,id_sub:localStorage.id_sub,},
				success: function (data){
					var result = JSON.parse(data);
					console.log(result);
					var text ='';
					
					for (var i = 0; i < result.length; i++) { 
						text +=  '<li>';
							for (var j = 1; j < result[i].length; j++) { 
								var text1='';
								if($.isArray(result[i][j])){
									text1 = result[i][j][0];
									var online = "fa-check-circle"; color ="white";
									if (result[i][j][2] == 0){
										online = "fa-times-circle";
										color = "orange";
									}
									text +='<div class="enable" ><span class="menu-icons portfolio"><i class="fa '+online+'" style="color:'+color+'"></i> </span>'+
										'<h3 style="background:rgb(27, 86, 132);color:'+color+'"> Thủy điện - '+ text1+ '</h3>'+
											'<span class="the-btn"><i class="fa fa-plus"></i></span>'+ 
									'</div>';
									for (var k = 3; k < result[i][j].length; k++) {
										text +=	'<ul>';									
										for (var n = 0; n < result[i][j][k].length; n++) { 
												var livalue = result[i][j][k][n][1]+'';
												console.log( livalue);
												text +=	'<li data-serial="'+livalue+'"><a href="index_current_meter.html" id="name_meter"> <span class="menu-icons-a portfolio" style="color:rgb(27, 86, 132)"><i class="fa-a fa-arrow-circle-right"></i> </span >'+result[i][j][k][n][2]+'</a></li>'+
														'</li>';
										}
										text +=	'</ul>';
									}
								} else {
									text1 = result[i][j];
									text +='<div><span class="menu-icons portfolio"><img src="img/evn.png" style="width:100%;height:100%; "></img> </span>'+
									'<h3 style="text-transform:uppercase">'+ text1+ '</h3>'+
								'</div>';
								}
							}
							
							
						text += '</li>';
					}
					$("#toggle").html(text);
					//loaded();
					myScroll.refresh();
					$.getScript("js/index.action.js");
					remove_ajax_img_loading(100);
				},
				error: function (e){
					alert('error');
				}
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
		
