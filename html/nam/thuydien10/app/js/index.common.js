
var img_url = 'images/ajax-loading.gif';
var load_story_list = function(){
	ajax_img_loading(img_url);
//alert('load_story_list');
	db.transaction(function (tx) {
	var storylist_table_name = 'story_list';
		var sql = 'SELECT * FROM '+storylist_table_name;
		console.log('Get story list from offline web database');
		tx.executeSql(sql, 
				[], 
				function (tx, results) {								//success
					console.log(results);
					var len = results.rows.length, i;
					var html='<ul>';
						for (i = 0; i < len; i++){
							var items = results.rows.item(i);
							
							var story_id = items['story_id']? items['story_id'] : 0;
							var story_img = items['story_img']? items['story_img'] : '';
							var story_name = items['story_name']? items['story_name'] : '';
							var author_name = items['story_author']? items['story_author'] : '';
							var story_view = items['story_view']? items['story_view'] : 0;
							var story_count_chapter = items['story_count_chapter']? items['story_count_chapter'] : 0;
							var story_type = items['story_type']? items['story_type'] : 0;
							var story_tag = items['story_tag']? items['story_tag'] : 0;
							var story_hot_tag = items['story_hot_tag']? items['story_hot_tag'] : 0;
							///////////////////////////
							//show content to webview//
							///////////////////////////
							html+= '<li data-storyid ="'+story_id+'" data-storyname ="'+story_name+'" class="parent"><div class="story_list_details"><div class="list_img"><a ><img src="data:image/jpg;base64,'+story_img+'" alt="zxc"/></a></div><div class="list_details" ><a class="name_story" >'+story_name+'</a><span class="name_author">Tác giả: '+author_name+'</span> <span class="info_story">Số chương: '+story_count_chapter+' Lượt xem: '+story_view+'</span></div><div class="outer"><div class="inner"> <img class="arrow_detail" src="app/css/img/arrow.png" /></div></div></div></li>' ;
						}
						console.log('Get story list from offline web database success and display into webview');
						html = html+'</ul>';
						$("#scroller").html(html);
						loaded();
						$.getScript("app/js/runtime/jquery.li.tap.js");
				},
				function(){downloadstorylist()}									//error ??? right sequent
		);
	});	
}
/*
	get online and save to web database
*/
var downloadstorylist = function(){
	ajax_img_loading(img_url);
	console.log('offline error and go online - current st id '+localStorage.save_current_story_id);
	$.ajax({
		url:api_link+'/index.get.story.list.php',
		type:"GET",
		data:{story_id:localStorage.save_current_story_id},
		success:function(data){
			console.log('ajax success'+data);
			var storylist = JSON.parse(data);
			///////////////////////////
			//show content to webview//
			///////////////////////////
			var index = 0;
			var html='<ul>';
			$.each(storylist,function(index){
				var story_id =storylist[index]['story_id'], story_img =storylist[index]['story_img'];
				var story_name =storylist[index]['story_name'], author_name = storylist[index]['story_author'];
				var story_view = storylist[index]['story_view'], story_count_chapter = storylist[index]['story_count_chapter'];
				var story_type = storylist[index]['story_type'], story_tag = storylist[index]['story_tag'],story_hot_tag = storylist[index]['story_hot_tag'];
				//console.log('document: story_id '+story_id+ ' story_name : '+story_name);
				html+= '<li data-storyid ="'+story_id+'" data-storyname ="'+story_name+'" class="parent"><div class="story_list_details"><div class="list_img"><a ><img src="data:image/jpg;base64,'+story_img+'" alt="zxc"/></a></div><div class="list_details" ><a class="name_story" >'+story_name+'</a><span class="name_author">Tác gia: '+author_name+'</span> <span class="info_story">So chuong: '+story_count_chapter+' Luot xem: '+story_view+'</span></div><div class="outer"><div class="inner"> <img class="arrow_detail" src="app/css/img/arrow.png" /></div></div></div></li>' ;
			});
			//console.log(html);
			html +='</ul>';
			$("#scroller").html(html);
			loaded();
			$.getScript("app/js/runtime/jquery.li.tap.js");
			remove_ajax_img_loading(300);
			////////////////////////
			//save to web database//
			////////////////////////
			var index = 0;
			db.transaction(function (tx) {
				var table_story_list = 'story_list';
				var sql = 'DROP TABLE IF EXISTS '+table_story_list;
				tx.executeSql(sql,[],
					function(rs){													//success
						console.log('rs '+rs);
						sql = 	'CREATE TABLE IF NOT EXISTS '+table_story_list+' (story_id INTEGER UNIQUE, story_img TEXT, story_name VARCHAR(200), story_author VARCHAR(200),story_view INTEGER, story_count_chapter INTEGER, story_type INTEGER, story_tag INTEGER, story_hot_tag INTEGER)';
						tx.executeSql(sql);
						console.log(sql);
						var index = 0;
						console.log(storylist);
						add_storylist_to_db(index,storylist,table_story_list);
					},
					function(e){console.log('e '+e)}								//error
				);									
			});
			/**
			* pull to update
			**/
			setTimeout(function(){$(".betweenable").remove();console.log('call remove ajax div function finish add and remove box-shadow css');},500);
	
		},
		error: function(e){
			console.log(' e '+e);//alert(data);
		}
	});	
};
var add_storylist_to_db =function(index,data_arr, table_name){
	if (data_arr[index]!=undefined){console.log('data_arr['+index+'] ok')}
	var items = data_arr[index];
	
	if (data_arr[index]!=undefined){
		if (index < data_arr.length){
			console.log(index+' - '+data_arr.length+' - '+data_arr[index].story_name+' - '+table_name);
			var story_id = items['story_id']? items['story_id'] : 0;
			var story_img = items['story_img']? items['story_img'] : '';
			var story_name = items['story_name']? items['story_name'] : '';
			var author_name = items['story_author']? items['story_author'] : '';
			var story_view = items['story_view']? items['story_view'] : 0;
			var story_count_chapter = items['story_count_chapter']? items['story_count_chapter'] : 0;
			var story_type = items['story_type']? items['story_type'] : 0;
			var story_tag = items['story_tag']? items['story_tag'] : 0;
			var story_hot_tag = items['story_hot_tag']? items['story_hot_tag'] : 0;
			
			db.transaction(function (tx) {
				var sql1 = 'INSERT INTO '+table_name+' (story_id, story_img, story_name, story_author, story_view, story_count_chapter, story_type, story_tag, story_hot_tag) VALUES (?,?,?,?,?,?,?,?,?)';
				tx.executeSql(
					sql1,
					[story_id,story_img,story_name,author_name,story_view,story_count_chapter,story_type,story_tag,story_hot_tag],
					add_storylist_to_db(index,data_arr,table_name), 		  		//success
					null															//error
				);
				console.log(sql1);
			}
			);
			index++;
			if (index >= data_arr.length) {remove_ajax_img_loading(800);}
		} 
	}
}

$(document).ready(function() {
	load_story_list();			//init storylist
	save_storylist_arr();		//save  storylist in "localStorage.sort_storylist" for sorting
});
var capnhattruyen = function(){
	console.log('Cập nhật truyện');
	ajax_img_loading('images/ajax-loading.gif');
	downloadstorylist();
	//remove_ajax_img_loading(300);
}



/*
	- Phần chapter list tự động get chapter_id và chapter_name save to web database 
	- Phần chapter_content thì pải nhấn nút download mới tải về ko tự động
	- Lấy dữ liệu từ web database 
	- thành công thì gọi hàm: success function (tx, results)
	- thất bại thì gọi hàm error: chapter_list_query_fail(story_id, story_name)
*/
var chapter_list = function(story_id, story_name){
	console.log('chapter_list '+story_name); //return false;
	ajax_img_loading(img_url);
	localStorage.save_current_story_id=story_id;
	var story_number = 'story_number_'+story_id;
	update_view_count(story_id);
	db.transaction(function (tx) {
		var sql = 'SELECT chapter_index, chapter_name FROM '+story_number;
		console.log (sql);
		tx.executeSql(sql, 
				[], 
				function (tx, results) {								//success function - có dữ liệu offline
					console.log(results);
					var len = results.rows.length, i;
					console.log('chapter count '+len);
					var html='<ul>';
					for (i = 0; i < len; i++){
						var items = results.rows.item(i);
						var chapter_index = items.chapter_index;
						var chapter_name = items.chapter_name;
						html+= '<li onclick="javascript:get_chapter_content('+story_id+','+chapter_index+',\''+chapter_name+'\');" class="parent_chapter"><span class="chapter_index"> Chương '+chapter_index+': </span><span class="chapter_name"> '+chapter_name+'</span> </li>';
					}
					html += '</ul>';
					/*
						dua chapter list vào hapter_list.html
					*/
					if (len!=0){										//Trường hợp lấy giá trị bằng null||''||undefined => tiếp tục ajax
						localStorage.chapter_list = html;
						window.location.href="app/chapterlist.html";
					} else if(len==0) {
						chapter_list_query_fail(story_id, story_name)
					}
					
				},
				chapter_list_query_fail(story_id, story_name) 			//error function - không có dữ liệu offline
		);
	});	
}

/*
	- Khi không load dc web database thì chuyển qua ajax đến server 
	- thành công thì gọi hàm success 
	- thất bại thì gọi hàm error
*/
var chapter_list_query_fail = function(story_id, story_name){
			localStorage.story_name = story_name;
			console.log('chapter_list_query_fail     '+story_id + story_name+' -- '+api_link+'/index.chapter.list.php');
					$.ajax({
						url:api_link+'/chapterlist.download.story.php',
						type:"GET",
						data:{story_id:story_id},
						success:function(data){
							items = JSON.parse(data);
							//console.log(items);
							
							var index = 0;
							var html='<ul>';
							$.each(items,function(index){
								var chapter_index = items[index]['chapter_id'];
								var chapter_name = items[index]['chapter_name'];
								html+= '<li onclick="javascript:get_chapter_content('+story_id+','+chapter_index+',\''+chapter_name+'\');" class="parent_chapter"><span class="chapter_index"> Chương '+chapter_index+': </span><span class="chapter_name"> '+chapter_name+'</span> </li>';
							});
							html += '</ul>';
							var count = 0;
							db.transaction(function (tx) {
								var story_number = 'story_number_'+story_id;
								tx.executeSql('DROP TABLE IF EXISTS '+story_number);
								sql = 	'CREATE TABLE IF NOT EXISTS '+story_number+' (chapter_index INTEGER UNIQUE, chapter_name VARCHAR(200))';
								tx.executeSql(sql);
								//console.log(sql);
								var index_ = 0;
								//console.log(items);
								add_chapterlist_to_db(index_, items, story_number);
							});
							
							/*
								dua chapter list vào chapter_list.html
							*/
							localStorage.chapter_list = html;
							//window.location.href="app/chapterlist.html";
						},
						error:function(e){
							console.log('e '+e);	//alert('There is no connection!'); // thành công chuyển sang chapterlist.html >> vẫn hiển thị alert ?? @@
						}
					});			
};

var add_chapterlist_to_db =function(index,content_arr, table_name){
	if (index < content_arr.length&&content_arr[index]['chapter_id']!=undefined){
		var chapterindex = content_arr[index]['chapter_id']? content_arr[index]['chapter_id'] : 1;
		var chaptername = content_arr[index]['chapter_name']? content_arr[index]['chapter_name'] : '';
		db.transaction(function (tx) {
			var sql1 = 'INSERT INTO '+table_name+' (chapter_index, chapter_name) VALUES (?,?)';
			tx.executeSql(
				sql1,
				[chapterindex,chaptername],
				add_chapterlist_to_db(index,content_arr,table_name), 			//success
				function(e){
					console.log(e);		 										//fail
				}
			);
			console.log(sql1);
		}
		);
		index++;
		if (index >= content_arr.length){
			setTimeout(function(){ window.location.href="app/chapterlist.html"; },1000);
		}
	}
}

var update_view_count =  function(story_id){
	$.ajax({
		url:api_link+'/update.view.count.php',
		type:"GET",
		data:{story_id:story_id},
		success:function(data){
			console.log('rs '+data);
			//alert('rs '+data);
		},
		error:function(e){
			console.log('e '+e);	//alert('There is no connection!'); // thành công chuyển sang chapterlist.html >> vẫn hiển thị alert ?? @@
		}
	});	
}
