//alert('loading js ok');
/*******************************************************/
var img_url = 'img/loading_c.gif';
localStorage.loading_ajax_showing = 0;
var ajax_img_loading = function(img_url){
	var showing = localStorage.loading_ajax_showing;
	if (showing == 0){
	var _x = $(window).width()/2, _y = $(window).height()/2;
	//alert(_x+'px'+_y+'px');
	var html = '<div id="ajax_loading" style="opacity:0.86; height: 100px; background:#ccc border-radius:15px; position: absolute; z-index: 9999;left: '+_x+'px;top: '+_y+'px;margin: -50px 0 0 -50px;">'+
					'<img style=" height: 30px;width: 30px; vertical-align: middle;"'+
						'src="'+img_url+'" > <a style="font-weight:bold; font-size:14px;color:#00557F">Loading</a>'+
				'</div></div>';
	$("body").append(html);
	console.log('ajax_img_loading');
	localStorage.loading_ajax_showing = 1;
	} else {
		return false;
	}
}
//ajax_img_loading(img_url);
/*******************************************************/
var remove_ajax_img_loading = function(time){
	var showing = localStorage.loading_ajax_showing;
	if (showing == 1){
		$('#ajax_loading').fadeOut(time,function(){$('#ajax_loading').remove();localStorage.loading_ajax_showing = 0;} );
		console.log('remove ajax_img_loading');
	}
}
//setTimeout(function(){remove_ajax_img_loading(100);},300);
