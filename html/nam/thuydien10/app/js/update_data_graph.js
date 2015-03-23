$(document).ready(function() {
	build_chart('http://mdms.npc.com.vn/mobile/server/geti_meter.php','','','Ipha','container-i');
	build_chart('http://mdms.npc.com.vn/mobile/server/getu_meter.php','','','Upha','container-u');
});

var chart;
function build_chart(filename,namey,namegraph,nameseries,idname){
vpw = $(window).width()/2;
vph = $(window).height()/2;
set_loading_pos(vpw,vph);
var options = {
					chart: {
						renderTo: idname,
						defaultSeriesType: 'line',
						marginRight: 10,
						marginBottom: 60
					},
					title: {
						style:{
						color: '#00496B',
						font:'Arial',
						fontWeight: 'bold',
						fontSize:'14px'
						},
						text: null,
						x: -25 //center
					},
					subtitle: {
						text: '',
						x: -20
					},
					xAxis: {
						type: 'datetime',
						tickInterval: 3600 * 250, // one hour
						tickWidth: 0,
						gridLineWidth: 1,
						labels: {
							align: 'center',
							x: -3,
							y: 28,
							rotation:90,
							formatter: function() {
								return Highcharts.dateFormat('%H:%M:%S', this.value);
							}						
						}
					},
					yAxis: {
						title: null,
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					tooltip: {
					//	formatter: function() {
				    //            return  Highcharts.dateFormat('%H:%M:%S', this.x) +': <b>'+ this.y + '</b>';
					//	}
						shared: true,
						crosshairs: true
					},
					
					legend: {
						layout: 'horizontal',
						align: 'bottom',
						verticalAlign: 'top',
						x: -10,
						y: -10,
						borderWidth: 0
					},
					series: [{
						name: nameseries + 'A'
					},
					{
						name: nameseries + 'B'
					},
					{
						name: nameseries + 'C'
					},
					
					] 
				}
				
				var current_meter_serial = localStorage.current_meter_serial;
				$.ajax ({
				url:filename, 
				type: 'GET',
				data: {serial:current_meter_serial},
				success: function (tsv){
				console.log(tsv);
					var lines = [];
					trafficA = [];
					trafficB = [];
					trafficC = [];
					try {
						// split the data return into lines and parse them
						tsv = tsv.split(/\n/g);
						jQuery.each(tsv, function(index, line) {
							line = line.split(/\t/);
							date = Date.parse(line[0] +' UTC');
							trafficA.push([
								date,
								parseFloat(line[1].replace(',', ''), 10)
							]);
							trafficB.push([
								date,
								parseFloat(line[2].replace(',', ''), 10)
							]);
							trafficC.push([
								date,
								parseFloat(line[3].replace(',', ''), 10)
							]);
						});
					} catch (e) {  }
					options.series[0].data = trafficA;
					options.series[1].data = trafficB;
					options.series[2].data = trafficC;
					chart = new Highcharts.Chart(options);
					var x=-2000, y=-2000;
					set_loading_pos(x,y);
				},
				error: function (e){
					alert('error');
				}
				});
}

function set_loading_pos(x,y){
	$('#loading').css({'top': y});
	$('#loading').css({'left': x});
	//$('loading').html('loading');
}