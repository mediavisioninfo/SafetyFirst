console.log("userchart js");
//new column charts
var options55 = {
	series: [{
		name: "Yearly userr  Profit",
		data: [{
			x: "Jan",
			y: 1500
		}, {
			x: "Feb",
			y: 3000
		}, {
			x: "Mar",
			y: 1800
		}, {
			x: "Apr",
			y: 3000,
			fillColor: vihoAdminConfig.secondary,
		}, {
			x: "May",
			y: 1800
		}, {
			x: "Jun",
			y: 1500
		}, {
			x: "Jul",
			y: 2500
		}, {
			x: "Sep",
			y: 1500,
			fillColor: vihoAdminConfig.secondary,
		}, {
			x: "Oct",
			y: 2000
		}]
	}],
	chart: {
		height: 250,
		type: "bar",
		toolbar: {
			show: false,
		},
	},
	plotOptions: {
		bar: {
			horizontal: false,
			columnWidth: "30%",
			startingShape: "rounded",
			endingShape: "rounded",
			colors: {
				backgroundBarColors: ["#e5edef"],
				backgroundBarOpacity: 1,
				backgroundBarRadius: 9
			}
		},
	},
	stroke: {
		show: false,
	},
	dataLabels: {
		enabled: false
	},
	fill: {
		opacity: 1
	},
	xaxis: {
		// type: "datetime",
		axisBorder: {
			show: false
		},
		labels: {
			show: true,
		},
		axisTicks: {
			show: false,
		},
	},
	yaxis: {
		labels: {
			show: false,
		}
	},
	colors: [vihoAdminConfig.primary]
};
var chart55 = new ApexCharts(document.querySelector("#user-activation-dash-2"), options55);
chart55.render();
