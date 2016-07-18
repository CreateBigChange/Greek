@include('alpha.header')




<!--main content start-->
<section id="main-content">
<section class="wrapper">
<!--state overview start-->
<div class="row state-overview">
	<div class="col-lg-3 col-sm-6">
		<section class="panel">
		<div class="symbol terques">
			<i class="icon-user"></i>
		</div>
		<div class="value">
			<h1 class="count">
				0
			</h1>
			<p>New Users</p>
		</div>
		</section>
	</div>
	<div class="col-lg-3 col-sm-6">
		<section class="panel">
		<div class="symbol red">
			<i class="icon-tags"></i>
		</div>
		<div class="value">
			<h1 class=" count2">
				0
			</h1>
			<p>Sales</p>
		</div>
		</section>
	</div>
	<div class="col-lg-3 col-sm-6">
		<section class="panel">
		<div class="symbol yellow">
			<i class="icon-shopping-cart"></i>
		</div>
		<div class="value">
			<h1 class=" count3">
				0
			</h1>
			<p>New Order</p>
		</div>
		</section>
	</div>
	<div class="col-lg-3 col-sm-6">
		<section class="panel">
		<div class="symbol blue">
			<i class="icon-bar-chart"></i>
		</div>
		<div class="value">
			<h1 class=" count4">
				0
			</h1>
			<p>Total Profit</p>
		</div>
		</section>
	</div>
</div>
<!--state overview end-->

< <div id="show" style="height: 600px;width:1000px;"></div>

<script type="text/javascript">
var dom = document.getElementById("show");
var myChart = echarts.init(dom);
var app = {};
option = null;
var base = new Date(2014, 9, 3);
var oneDay = 24 * 3600 * 1000;
var date = [];

var data = [Math.random() * 150];
var nowTime = new Date();
function addData(shift) {
    now = [new Date().getHours(), new Date().getMinutes(), new Date().getSeconds()].join(':');
    date.push(now);
    data.push((Math.random() - 0.4) * 10 + data[data.length - 1]);

    if (shift) {
        date.shift();
        data.shift();
    }

    now = new Date(+new Date(now) + oneDay);
}

for (var i = 1; i < 100; i++) {
    addData();
}

option = {
    xAxis: {
        type: 'category',
        boundaryGap: false,
        data: date
    },
    yAxis: {
        boundaryGap: [0, '50%'],
        type: 'value'
    },
    series: [
        {
            name:'成交',
            type:'line',
            smooth:true,
            symbol: 'none',
            stack: 'a',
            areaStyle: {
                normal: {}
            },
            data: data
        }
    ]
};

app.timeTicket = setInterval(function () {
    addData(true);
    myChart.setOption({
        xAxis: {
            data: date
        },
        series: [{
            name:'成交',
            data: data
        }]
    });
}, 1000);;
if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
    </script>
</section>
</section>
<!--main content end-->

@include('alpha.footer')
